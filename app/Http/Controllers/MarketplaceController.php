<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\MarketplaceBid;
use App\Models\MarketplaceListing;
use App\Models\MarketplaceOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    /**
     * Hall d'enchères — liste des annonces actives
     */
    public function index(Request $request): View
    {
        // Expirer les annonces échues avant affichage
        $this->expireListings();

        $query = MarketplaceListing::with(['card.faction', 'seller', 'currentBidder'])
            ->where('status', 'active')
            ->where('expires_at', '>', now());

        // Filtres
        if ($request->filled('rarity')) {
            $query->whereHas('card', fn($q) => $q->where('rarity', $request->rarity));
        }
        if ($request->filled('faction')) {
            $query->whereHas('card', fn($q) => $q->where('faction_id', $request->faction));
        }
        if ($request->filled('sort')) {
            match($request->sort) {
                'price_asc'    => $query->orderBy('current_bid')->orderBy('starting_price'),
                'price_desc'   => $query->orderByDesc('current_bid')->orderByDesc('starting_price'),
                'ending_soon'  => $query->orderBy('expires_at'),
                default        => $query->orderByDesc('created_at'),
            };
        } else {
            $query->orderByDesc('created_at');
        }

        $listings = $query->paginate(12)->withQueryString();
        $factions = \App\Models\Faction::orderBy('name')->get();

        return view('marketplace.index', compact('listings', 'factions'));
    }

    /**
     * Formulaire de création d'annonce
     */
    public function create(): View
    {
        $user = auth()->user();

        // Cartes possédées, non déjà en vente
        $listedCardIds = MarketplaceListing::where('seller_id', $user->id)
            ->where('status', 'active')
            ->pluck('card_id');

        $cards = $user->cards()
            ->with('faction')
            ->whereNotIn('cards.id', $listedCardIds)
            ->get();

        $minPrices = MarketplaceListing::MIN_PRICES;

        return view('marketplace.create', compact('cards', 'minPrices'));
    }

    /**
     * Enregistre une nouvelle annonce
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'card_id'       => 'required|exists:cards,id',
            'starting_price' => 'required|integer|min:1',
            'buyout_price'  => 'required|integer',
        ]);

        $card = Card::findOrFail($request->card_id);

        // Vérifier que le joueur possède la carte
        $userCard = $user->cards()->where('cards.id', $card->id)->first();
        if (!$userCard) {
            return back()->with('error', 'Vous ne possédez pas cette carte.');
        }

        // Vérifier prix minimum selon rareté
        $minPrice = MarketplaceListing::MIN_PRICES[$card->rarity] ?? 50;
        if ($request->starting_price < $minPrice) {
            return back()->with('error', "Prix de départ minimum pour une carte {$card->rarity} : {$minPrice} 🪙")->withInput();
        }

        // Buyout doit être supérieur au prix de départ
        if ($request->buyout_price <= $request->starting_price) {
            return back()->with('error', 'Le prix d\'achat immédiat doit être supérieur au prix de départ.')->withInput();
        }

        // Vérifier qu'elle n'est pas déjà en vente
        $alreadyListed = MarketplaceListing::where('seller_id', $user->id)
            ->where('card_id', $card->id)
            ->where('status', 'active')
            ->exists();
        if ($alreadyListed) {
            return back()->with('error', 'Cette carte est déjà en vente sur le marché.')->withInput();
        }

        // Retirer 1 exemplaire de la collection (carte en escrow)
        $user->removeCard($card->id, 1);

        // Créer l'annonce
        MarketplaceListing::create([
            'seller_id'      => $user->id,
            'card_id'        => $card->id,
            'starting_price' => $request->starting_price,
            'buyout_price'   => $request->buyout_price,
            'current_bid'    => 0,
            'expires_at'     => now()->addHours(MarketplaceListing::DURATION_HOURS),
        ]);

        return redirect()->route('marketplace.index')
            ->with('success', "✅ {$card->name} mise en vente pour 48h !");
    }

    /**
     * Détail d'une annonce
     */
    public function show(MarketplaceListing $listing): View
    {
        $this->expireListings();
        $listing->load(['card.faction', 'seller', 'currentBidder', 'bids.bidder', 'offers.offerer', 'offers.offeredCard.faction']);

        $user = auth()->user();

        // Cartes que l'acheteur peut proposer en échange (hors carte déjà en vente)
        $myListedCardIds = MarketplaceListing::where('seller_id', $user->id)
            ->where('status', 'active')
            ->pluck('card_id');

        $myCards = $user->cards()
            ->with('faction')
            ->whereNotIn('cards.id', $myListedCardIds)
            ->where('cards.id', '!=', $listing->card_id)
            ->get();

        // Offre d'échange en attente de l'utilisateur courant
        $myPendingOffer = $listing->offers
            ->where('offerer_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('marketplace.show', compact('listing', 'myCards', 'myPendingOffer'));
    }

    /**
     * Placer une enchère
     */
    public function bid(Request $request, MarketplaceListing $listing): RedirectResponse
    {
        $user = auth()->user();

        if (!$listing->isActive()) {
            return back()->with('error', 'Cette annonce n\'est plus active.');
        }
        if ($listing->seller_id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas enchérir sur votre propre annonce.');
        }

        $minBid = $listing->nextMinimumBid();

        $request->validate([
            'amount' => "required|integer|min:{$minBid}",
        ]);

        $amount = (int) $request->amount;

        // Vérifier que l'acheteur a les fonds
        if ($user->coins < $amount) {
            return back()->with('error', 'Vous n\'avez pas assez de pièces.');
        }

        // Rembourser l'ancien enchérisseur
        if ($listing->current_bid > 0 && $listing->currentBidder) {
            $listing->currentBidder->addCoins($listing->current_bid);

            // Notifier l'ancien enchérisseur (surenchéri)
            \App\Models\MailboxMessage::create([
                'user_id'      => $listing->current_bidder_id,
                'from_user_id' => $user->id,
                'type'         => 'outbid',
                'title'        => '⚠️ Vous avez été surenchéri !',
                'body'         => "**{$user->name}** a placé une enchère de {$amount} 🪙 sur **{$listing->card->name}**. Vos {$listing->current_bid} 🪙 vous ont été remboursés.",
                'data'         => ['listing_id' => $listing->id, 'amount' => $amount],
            ]);
        }

        // Débiter le nouvel enchérisseur
        $user->spendCoins($amount);

        // Enregistrer l'enchère
        MarketplaceBid::create([
            'listing_id' => $listing->id,
            'bidder_id'  => $user->id,
            'amount'     => $amount,
        ]);

        // Mettre à jour l'annonce
        $listing->update([
            'current_bid'        => $amount,
            'current_bidder_id'  => $user->id,
        ]);

        // Notifier le vendeur
        \App\Models\MailboxMessage::create([
            'user_id'      => $listing->seller_id,
            'from_user_id' => $user->id,
            'type'         => 'bid_received',
            'title'        => '🔔 Nouvelle enchère !',
            'body'         => "**{$user->name}** a enchéri {$amount} 🪙 sur votre annonce pour **{$listing->card->name}**.",
            'data'         => ['listing_id' => $listing->id, 'amount' => $amount],
        ]);

        return back()->with('success', "Enchère de " . number_format($amount) . " 🪙 placée avec succès !");
    }

    /**
     * Achat immédiat
     */
    public function buyout(MarketplaceListing $listing): RedirectResponse
    {
        $user = auth()->user();

        if (!$listing->isActive()) {
            return back()->with('error', 'Cette annonce n\'est plus active.');
        }
        if ($listing->seller_id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas acheter votre propre annonce.');
        }

        $price  = $listing->buyout_price;
        $payout = $listing->sellerReceives($price);

        if ($user->coins < $price) {
            return back()->with('error', 'Vous n\'avez pas assez de pièces.');
        }

        // Rembourser l'enchérisseur actuel si besoin
        if ($listing->current_bid > 0 && $listing->currentBidder) {
            $listing->currentBidder->addCoins($listing->current_bid);
        }

        // Débiter l'acheteur
        $user->spendCoins($price);

        // Transférer la carte
        $user->addCard($listing->card, 1);

        // Payer le vendeur (moins commission)
        $listing->seller->addCoins($payout);

        $listing->update(['status' => 'sold']);

        // Notifier acheteur
        \App\Models\MailboxMessage::create([
            'user_id'      => $user->id,
            'from_user_id' => $listing->seller_id,
            'type'         => 'buyout',
            'title'        => '💰 Achat effectué !',
            'body'         => "Vous avez acheté **{$listing->card->name}** pour {$price} 🪙. La carte a été ajoutée à votre collection.",
            'data'         => ['listing_id' => $listing->id, 'card_id' => $listing->card_id, 'amount' => $price],
        ]);

        // Notifier vendeur
        \App\Models\MailboxMessage::create([
            'user_id'      => $listing->seller_id,
            'from_user_id' => $user->id,
            'type'         => 'auction_sold',
            'title'        => '💰 Carte achetée immédiatement !',
            'body'         => "**{$user->name}** a acheté **{$listing->card->name}** au prix d'achat immédiat ({$price} 🪙). Vous recevez {$payout} 🪙.",
            'data'         => ['listing_id' => $listing->id, 'amount' => $price, 'payout' => $payout],
        ]);

        return redirect()->route('marketplace.index')
            ->with('success', "✅ Vous avez acheté {$listing->card->name} pour " . number_format($price) . " 🪙 !");
    }

    /**
     * Proposer un échange de carte
     */
    public function offer(Request $request, MarketplaceListing $listing): RedirectResponse
    {
        $user = auth()->user();

        if (!$listing->isActive()) {
            return back()->with('error', 'Cette annonce n\'est plus active.');
        }
        if ($listing->seller_id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas proposer un échange sur votre propre annonce.');
        }

        // Une seule offre en attente par annonce/acheteur
        $existing = MarketplaceOffer::where('listing_id', $listing->id)
            ->where('offerer_id', $user->id)
            ->where('status', 'pending')
            ->exists();
        if ($existing) {
            return back()->with('error', 'Vous avez déjà une offre d\'échange en attente.');
        }

        $request->validate([
            'offered_card_id' => 'required|exists:cards,id',
            'message'         => 'nullable|string|max:255',
        ]);

        // Vérifier possession de la carte proposée
        $offeredCard = $user->cards()->where('cards.id', $request->offered_card_id)->first();
        if (!$offeredCard) {
            return back()->with('error', 'Vous ne possédez pas cette carte.');
        }

        MarketplaceOffer::create([
            'listing_id'      => $listing->id,
            'offerer_id'      => $user->id,
            'offered_card_id' => $request->offered_card_id,
            'message'         => $request->message,
            'status'          => 'pending',
        ]);

        // Notifier le vendeur
        \App\Models\MailboxMessage::create([
            'user_id'      => $listing->seller_id,
            'from_user_id' => $user->id,
            'type'         => 'offer_received',
            'title'        => '🤝 Proposition d\'échange reçue !',
            'body'         => "**{$user->name}** vous propose **{$offeredCard->name}** en échange de **{$listing->card->name}**." . ($request->message ? " Message : {$request->message}" : ''),
            'data'         => ['listing_id' => $listing->id, 'card_id' => $offeredCard->id],
        ]);

        return back()->with('success', 'Proposition d\'échange envoyée !');
    }

    /**
     * Accepter ou refuser une offre d'échange
     */
    public function respondOffer(Request $request, MarketplaceOffer $offer): RedirectResponse
    {
        $user    = auth()->user();
        $offer->load(['listing.card', 'listing.currentBidder', 'listing.seller', 'offeredCard']);
        $listing = $offer->listing;

        if ($listing->seller_id !== $user->id) {
            return back()->with('error', 'Action non autorisée.');
        }
        if ($offer->status !== 'pending') {
            return back()->with('error', 'Cette offre n\'est plus en attente.');
        }
        if (!$listing->isActive()) {
            return back()->with('error', 'L\'annonce n\'est plus active.');
        }

        $request->validate(['action' => 'required|in:accept,reject']);

        if ($request->action === 'accept') {
            // Rembourser enchérisseur si enchère en cours
            if ($listing->current_bid > 0 && $listing->currentBidder) {
                $listing->currentBidder->addCoins($listing->current_bid);
            }

            // Échange : vendeur reçoit la carte proposée, acheteur reçoit la carte de l'annonce
            $user->addCard($offer->offeredCard, 1);
            $offer->offerer->removeCard($offer->offered_card_id, 1);
            $offer->offerer->addCard($listing->card, 1);

            $listing->update(['status' => 'sold']);
            $offer->update(['status' => 'accepted']);

            // Refuser les autres offres en attente
            MarketplaceOffer::where('listing_id', $listing->id)
                ->where('id', '!=', $offer->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            // Notifier l'offrant
            \App\Models\MailboxMessage::create([
                'user_id'      => $offer->offerer_id,
                'from_user_id' => $user->id,
                'type'         => 'offer_accepted',
                'title'        => '✅ Échange accepté !',
                'body'         => "**{$user->name}** a accepté votre échange. Vous recevez **{$listing->card->name}** en échange de **{$offer->offeredCard->name}**.",
                'data'         => ['listing_id' => $listing->id],
            ]);

            return redirect()->route('marketplace.index')
                ->with('success', "Échange accepté ! Vous recevez {$offer->offeredCard->name}.");
        }

        // Refus
        $offer->update(['status' => 'rejected']);

        \App\Models\MailboxMessage::create([
            'user_id'      => $offer->offerer_id,
            'from_user_id' => $user->id,
            'type'         => 'offer_rejected',
            'title'        => '❌ Échange refusé',
            'body'         => "**{$user->name}** a refusé votre proposition d'échange de **{$offer->offeredCard->name}** contre **{$listing->card->name}**.",
            'data'         => ['listing_id' => $listing->id],
        ]);

        return back()->with('success', 'Offre d\'échange refusée.');
    }

    /**
     * Annule une annonce (vendeur uniquement, si pas d'enchère en cours)
     */
    public function cancel(MarketplaceListing $listing): RedirectResponse
    {
        $user = auth()->user();

        if ($listing->seller_id !== $user->id) {
            return back()->with('error', 'Action non autorisée.');
        }
        if ($listing->current_bid > 0) {
            return back()->with('error', 'Impossible d\'annuler une annonce avec des enchères en cours.');
        }
        if ($listing->status !== 'active') {
            return back()->with('error', 'Cette annonce n\'est plus active.');
        }

        // Rendre la carte au vendeur
        $user->addCard($listing->card, 1);
        $listing->update(['status' => 'cancelled']);

        return redirect()->route('marketplace.index')
            ->with('success', 'Annonce annulée. La carte a été restituée.');
    }

    // ── Expiration automatique ────────────────────────────────────────────────

    private function expireListings(): void
    {
        $expired = MarketplaceListing::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->with(['card', 'seller', 'currentBidder'])
            ->get();

        foreach ($expired as $listing) {
            if ($listing->current_bid > 0 && $listing->currentBidder) {
                // Il y a un gagnant → finaliser la vente
                $this->finalizeAuction($listing);
            } else {
                // Aucune enchère → expirer et rendre la carte
                $listing->seller->addCard($listing->card, 1);
                $listing->update(['status' => 'expired']);

                // Notifier le vendeur
                \App\Models\MailboxMessage::create([
                    'user_id' => $listing->seller_id,
                    'type'    => 'listing_expired',
                    'title'   => '⏰ Annonce expirée',
                    'body'    => "Votre annonce pour **{$listing->card->name}** a expiré sans enchère. La carte vous a été restituée.",
                    'data'    => ['listing_id' => $listing->id, 'card_id' => $listing->card_id],
                ]);
            }
        }
    }

    private function finalizeAuction(MarketplaceListing $listing): void
    {
        $winner  = $listing->currentBidder;
        $seller  = $listing->seller;
        $amount  = $listing->current_bid;
        $payout  = $listing->sellerReceives($amount);

        // Transfert carte → gagnant
        $winner->addCard($listing->card, 1);

        // Paiement vendeur (moins commission)
        $seller->addCoins($payout);

        $listing->update(['status' => 'sold']);

        // Notifier gagnant
        \App\Models\MailboxMessage::create([
            'user_id' => $winner->id,
            'type'    => 'auction_won',
            'title'   => '🏆 Enchère remportée !',
            'body'    => "Félicitations ! Vous avez remporté **{$listing->card->name}** pour {$amount} 🪙. La carte a été ajoutée à votre collection.",
            'data'    => ['listing_id' => $listing->id, 'card_id' => $listing->card_id, 'amount' => $amount],
        ]);

        // Notifier vendeur
        \App\Models\MailboxMessage::create([
            'user_id' => $seller->id,
            'type'    => 'auction_sold',
            'title'   => '💰 Carte vendue !',
            'body'    => "**{$listing->card->name}** a été vendue pour {$amount} 🪙. Vous recevez {$payout} 🪙 (commission 10% déduite).",
            'data'    => ['listing_id' => $listing->id, 'amount' => $amount, 'payout' => $payout],
        ]);
    }
}

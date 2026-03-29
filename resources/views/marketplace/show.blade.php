<x-app-layout>
    <style>
        .detail-card {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            overflow: hidden;
        }
        .card-img-wrap {
            aspect-ratio: 2.5 / 3.5;
            overflow: hidden;
        }
        .card-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center top;
        }
        .rarity-badge {
            display: inline-block;
            font-size: 0.65rem; font-weight: 800;
            padding: 3px 8px; border-radius: 5px; text-transform: uppercase;
        }
        .rarity-common    { background: rgba(100,100,100,0.6); color: #ccc; }
        .rarity-rare      { background: rgba(59,130,246,0.6);  color: #bfdbfe; }
        .rarity-epic      { background: rgba(139,92,246,0.6);  color: #ddd6fe; }
        .rarity-legendary { background: rgba(245,158,11,0.6);  color: #fef3c7; }
        .rarity-mythic    { background: rgba(236,72,153,0.6);  color: #fce7f3; }
        .action-panel {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 1.2rem;
        }
        .tab-btn { transition: all 0.2s; }
        .tab-btn.active {
            background: rgba(251,191,36,0.15);
            border-color: rgba(251,191,36,0.5);
            color: #fbbf24;
        }
        .bid-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.4rem 0.6rem; border-radius: 8px;
        }
        .bid-row:nth-child(odd) { background: rgba(255,255,255,0.03); }
        .offer-card-pick {
            position: relative; border-radius: 10px; overflow: hidden;
            aspect-ratio: 2.5 / 3.5; cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s, transform 0.15s;
        }
        .offer-card-pick:hover { transform: translateY(-2px); }
        .offer-card-pick.selected { border-color: #fbbf24; box-shadow: 0 0 14px rgba(251,191,36,0.4); }
        .offer-card-pick img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }
        .timer { font-size: 0.8rem; font-weight: 700; }
        .timer.urgent { color: #f87171; }
        .timer.safe   { color: #6ee7b7; }
    </style>

    <div class="min-h-screen relative">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-800 via-gray-900 to-black"></div>
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-[0.10]" loading="eager">
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-10">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('marketplace.index') }}"
                   class="px-4 py-2 bg-white/10 text-white text-sm rounded-xl hover:bg-white/20 transition">
                    ← Retour
                </a>
                <h1 class="text-2xl font-bold text-white">🏛️ Hall des Enchères</h1>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-500/20 border border-green-500/40 rounded-xl text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-500/20 border border-red-500/40 rounded-xl text-red-300 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Colonne gauche : carte --}}
                <div class="lg:col-span-1">
                    <div class="detail-card">
                        <div class="card-img-wrap"
                             style="background: linear-gradient(135deg, {{ $listing->card->faction->color_primary ?? '#2d1b69' }}, {{ $listing->card->faction->color_secondary ?? '#4c1d95' }});">
                            @if($listing->card->image_primary)
                                <img src="{{ Storage::url($listing->card->image_primary) }}" alt="{{ $listing->card->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl">⚔️</div>
                            @endif
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="rarity-badge rarity-{{ $listing->card->rarity }}">{{ $listing->card->rarity }}</span>
                                <span class="text-gray-400 text-xs">{{ $listing->card->faction->name ?? '—' }}</span>
                            </div>
                            <h2 class="text-white font-bold text-lg">{{ $listing->card->name }}</h2>
                            @if($listing->card->description)
                                <p class="text-gray-400 text-xs leading-relaxed">{{ Str::limit($listing->card->description, 100) }}</p>
                            @endif

                            {{-- Stats --}}
                            @if($listing->card->attack || $listing->card->defense || $listing->card->hp)
                            <div class="flex gap-3 pt-1">
                                @if($listing->card->hp)
                                <div class="text-center">
                                    <div class="text-red-400 font-bold text-sm">{{ $listing->card->hp }}</div>
                                    <div class="text-gray-500 text-xs">HP</div>
                                </div>
                                @endif
                                @if($listing->card->attack)
                                <div class="text-center">
                                    <div class="text-orange-400 font-bold text-sm">{{ $listing->card->attack }}</div>
                                    <div class="text-gray-500 text-xs">ATK</div>
                                </div>
                                @endif
                                @if($listing->card->defense)
                                <div class="text-center">
                                    <div class="text-blue-400 font-bold text-sm">{{ $listing->card->defense }}</div>
                                    <div class="text-gray-500 text-xs">DEF</div>
                                </div>
                                @endif
                            </div>
                            @endif

                            {{-- Vendeur --}}
                            <div class="pt-2 border-t border-white/10 text-xs text-gray-400">
                                Vendeur : <span class="text-white font-semibold">{{ $listing->seller->name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Annuler (si je suis le vendeur, pas d'enchère) --}}
                    @if($listing->seller_id === auth()->id() && $listing->current_bid == 0 && $listing->isActive())
                    <form method="POST" action="{{ route('marketplace.cancel', $listing) }}" class="mt-3"
                          onsubmit="return confirm('Annuler cette annonce et récupérer votre carte ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-red-500/20 border border-red-500/30 text-red-400 text-sm rounded-xl hover:bg-red-500/30 transition">
                            🗑️ Annuler l'annonce
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Colonne droite : enchères & actions --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Prix & timer --}}
                    <div class="detail-card p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="space-y-3">
                                @if($listing->current_bid > 0)
                                <div>
                                    <div class="text-gray-400 text-xs mb-0.5">Enchère actuelle</div>
                                    <div class="text-yellow-400 font-bold text-2xl">{{ number_format($listing->current_bid) }} 🪙</div>
                                    @if($listing->currentBidder)
                                    <div class="text-gray-400 text-xs">par {{ $listing->currentBidder->name }}</div>
                                    @endif
                                </div>
                                @else
                                <div>
                                    <div class="text-gray-400 text-xs mb-0.5">Prix de départ</div>
                                    <div class="text-white font-bold text-2xl">{{ number_format($listing->starting_price) }} 🪙</div>
                                </div>
                                @endif
                                <div>
                                    <div class="text-gray-400 text-xs mb-0.5">Achat immédiat</div>
                                    <div class="text-green-400 font-bold text-xl">{{ number_format($listing->buyout_price) }} 🪙</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-gray-400 text-xs mb-1">Temps restant</div>
                                @php $urgent = $listing->expires_at->diffInHours() <= 12; @endphp
                                <div class="timer {{ $urgent ? 'urgent' : 'safe' }} text-2xl">
                                    {{ $listing->timeRemaining() }}
                                </div>
                                <div class="text-gray-500 text-xs mt-1">
                                    Expire le {{ $listing->expires_at->format('d/m à H\hi') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Onglets actions (seulement si acheteur) --}}
                    @if($listing->seller_id !== auth()->id() && $listing->isActive())
                    <div>
                        {{-- Tabs --}}
                        <div class="flex gap-2 mb-3">
                            <button type="button" onclick="showTab('bid')"
                                id="tab-bid"
                                class="tab-btn active px-4 py-2 text-sm font-bold border border-white/20 rounded-xl text-gray-300">
                                🔨 Enchérir
                            </button>
                            <button type="button" onclick="showTab('buyout')"
                                id="tab-buyout"
                                class="tab-btn px-4 py-2 text-sm font-bold border border-white/20 rounded-xl text-gray-300">
                                ⚡ Acheter
                            </button>
                            <button type="button" onclick="showTab('offer')"
                                id="tab-offer"
                                class="tab-btn px-4 py-2 text-sm font-bold border border-white/20 rounded-xl text-gray-300">
                                🤝 Échanger
                            </button>
                        </div>

                        {{-- Panel Enchérir --}}
                        <div id="panel-bid" class="action-panel">
                            @php $minBid = $listing->nextMinimumBid(); @endphp
                            <p class="text-gray-400 text-sm mb-3">
                                Enchère minimum : <span class="text-yellow-400 font-bold">{{ number_format($minBid) }} 🪙</span>
                                @if($listing->current_bid > 0)
                                <span class="text-gray-500 text-xs">(+5% de l'enchère actuelle)</span>
                                @endif
                            </p>
                            <form method="POST" action="{{ route('marketplace.bid', $listing) }}">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="number" name="amount" min="{{ $minBid }}" value="{{ $minBid }}"
                                           class="flex-1 bg-white/10 border border-white/20 text-white rounded-xl px-4 py-2.5 font-bold focus:outline-none focus:border-yellow-500/50"
                                           oninput="updateBidPayout(this.value)">
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition whitespace-nowrap">
                                        Enchérir
                                    </button>
                                </div>
                                <p class="text-gray-500 text-xs mt-2">
                                    Vos pièces : <span class="text-white font-semibold">{{ number_format(auth()->user()->coins) }} 🪙</span>
                                    &nbsp;·&nbsp;
                                    Si vous gagnez, vous payez le montant de votre enchère.
                                </p>
                            </form>
                        </div>

                        {{-- Panel Achat immédiat --}}
                        <div id="panel-buyout" class="action-panel hidden">
                            <p class="text-gray-300 text-sm mb-4">
                                Achetez <strong class="text-white">{{ $listing->card->name }}</strong> immédiatement pour
                                <strong class="text-green-400">{{ number_format($listing->buyout_price) }} 🪙</strong>.
                                L'annonce se ferme et la carte vous est transférée.
                            </p>
                            @if(auth()->user()->coins >= $listing->buyout_price)
                            <form method="POST" action="{{ route('marketplace.buyout', $listing) }}"
                                  onsubmit="return confirm('Confirmer l\'achat de {{ $listing->card->name }} pour {{ number_format($listing->buyout_price) }} 🪙 ?')">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold rounded-xl hover:from-green-400 hover:to-emerald-400 transition">
                                    ⚡ Acheter pour {{ number_format($listing->buyout_price) }} 🪙
                                </button>
                            </form>
                            @else
                            <div class="text-red-400 text-sm text-center py-2">
                                ❌ Pas assez de pièces (il vous manque {{ number_format($listing->buyout_price - auth()->user()->coins) }} 🪙)
                            </div>
                            @endif
                        </div>

                        {{-- Panel Échange --}}
                        <div id="panel-offer" class="action-panel hidden">
                            @if($myPendingOffer)
                                <div class="text-center py-3 text-yellow-400 text-sm">
                                    ⏳ Vous avez déjà une offre d'échange en attente
                                    (<strong>{{ $myPendingOffer->offeredCard->name }}</strong>).
                                </div>
                            @elseif($myCards->isEmpty())
                                <p class="text-gray-500 text-sm text-center py-3">Vous n'avez aucune carte disponible à proposer en échange.</p>
                            @else
                            <p class="text-gray-400 text-sm mb-3">Choisissez une carte à proposer en échange :</p>
                            <form method="POST" action="{{ route('marketplace.offer', $listing) }}" id="offerForm">
                                @csrf
                                <input type="hidden" name="offered_card_id" id="offeredCardId">

                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3 max-h-48 overflow-y-auto pr-1">
                                    @foreach($myCards as $card)
                                    <div class="offer-card-pick"
                                         data-card-id="{{ $card->id }}"
                                         onclick="selectOfferCard(this)"
                                         style="background: linear-gradient(135deg, {{ $card->faction->color_primary ?? '#2d1b69' }}, {{ $card->faction->color_secondary ?? '#4c1d95' }});">
                                        @if($card->image_primary)
                                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">⚔️</div>
                                        @endif
                                        <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.9),transparent);padding:0.3rem;font-size:0.5rem;font-weight:700;color:white;text-align:center;">
                                            {{ $card->name }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="message" maxlength="255" placeholder="Message (optionnel)"
                                           class="w-full bg-white/10 border border-white/20 text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-yellow-500/50 placeholder-gray-500">
                                </div>

                                <button type="submit" id="offerSubmit" disabled
                                    class="w-full py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                    🤝 Envoyer la proposition
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Mes offres reçues (si je suis vendeur) --}}
                    @elseif($listing->seller_id === auth()->id())
                    @php $pendingOffers = $listing->offers->where('status', 'pending'); @endphp
                    @if($pendingOffers->isNotEmpty())
                    <div class="detail-card p-5">
                        <h3 class="text-white font-bold mb-4">🤝 Propositions d'échange reçues ({{ $pendingOffers->count() }})</h3>
                        <div class="space-y-3">
                            @foreach($pendingOffers as $offer)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                <div class="w-12 shrink-0 rounded-lg overflow-hidden aspect-[2.5/3.5]"
                                     style="background: linear-gradient(135deg, {{ $offer->offeredCard->faction->color_primary ?? '#2d1b69' }}, {{ $offer->offeredCard->faction->color_secondary ?? '#4c1d95' }});">
                                    @if($offer->offeredCard->image_primary)
                                        <img src="{{ Storage::url($offer->offeredCard->image_primary) }}" alt="{{ $offer->offeredCard->name }}" class="w-full h-full object-cover object-top" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-lg">⚔️</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-white text-sm font-bold truncate">{{ $offer->offeredCard->name }}</div>
                                    <div class="text-gray-400 text-xs">par {{ $offer->offerer->name }}</div>
                                    @if($offer->message)
                                    <div class="text-gray-400 text-xs italic mt-0.5">"{{ $offer->message }}"</div>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('marketplace.offer.respond', $offer) }}" class="flex gap-2 shrink-0">
                                    @csrf
                                    <button type="submit" name="action" value="accept"
                                        class="px-3 py-1.5 bg-green-500/20 border border-green-500/30 text-green-400 text-xs font-bold rounded-lg hover:bg-green-500/30 transition">
                                        ✅ Accepter
                                    </button>
                                    <button type="submit" name="action" value="reject"
                                        class="px-3 py-1.5 bg-red-500/20 border border-red-500/30 text-red-400 text-xs font-bold rounded-lg hover:bg-red-500/30 transition">
                                        ❌ Refuser
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    {{-- Historique des enchères --}}
                    @if($listing->bids->isNotEmpty())
                    <div class="detail-card p-5">
                        <h3 class="text-white font-bold mb-3">📋 Historique des enchères</h3>
                        <div class="space-y-1">
                            @foreach($listing->bids->take(10) as $i => $bid)
                            <div class="bid-row">
                                <div class="flex items-center gap-2">
                                    @if($i === 0)
                                        <span class="text-xs">🥇</span>
                                    @else
                                        <span class="text-gray-600 text-xs">#{{ $i + 1 }}</span>
                                    @endif
                                    <span class="text-gray-300 text-sm">{{ $bid->bidder->name }}</span>
                                </div>
                                <div class="text-yellow-400 font-bold text-sm">{{ number_format($bid->amount) }} 🪙</div>
                            </div>
                            @endforeach
                            @if($listing->bids->count() > 10)
                            <div class="text-gray-600 text-xs text-center pt-1">+ {{ $listing->bids->count() - 10 }} autres enchères</div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
    function showTab(tab) {
        ['bid','buyout','offer'].forEach(t => {
            document.getElementById('panel-' + t).classList.add('hidden');
            document.getElementById('tab-' + t).classList.remove('active');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).classList.add('active');
    }

    function selectOfferCard(el) {
        document.querySelectorAll('.offer-card-pick').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('offeredCardId').value = el.dataset.cardId;
        document.getElementById('offerSubmit').disabled = false;
    }
    </script>
</x-app-layout>

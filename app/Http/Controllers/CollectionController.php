<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Affiche la collection du joueur (toutes les cartes, possédées et non possédées)
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Récupérer les cartes possédées par le joueur avec leur quantité
        $ownedCards = $user->cards()
            ->with(['faction', 'mainAttack'])
            ->get()
            ->keyBy('id');

        // Récupérer TOUTES les cartes du jeu
        $allCards = Card::with(['faction', 'mainAttack'])
            ->orderBy('name')
            ->get();

        // Marquer les cartes possédées et ajouter la quantité
        $allCards = $allCards->map(function ($card) use ($ownedCards) {
            $card->owned = $ownedCards->has($card->id);
            $card->owned_quantity = $card->owned ? $ownedCards->get($card->id)->pivot->quantity : 0;
            return $card;
        });

        // Trier : cartes possédées en premier, puis par nom
        $allCards = $allCards->sortBy([
            ['owned', 'desc'],
            ['name', 'asc'],
        ])->values();

        // Statistiques (basées sur les cartes possédées uniquement)
        $ownedCollection = $ownedCards->values();
        $stats = [
            'total_cards' => $ownedCollection->sum('pivot.quantity'),
            'unique_cards' => $ownedCollection->count(),
            'total_available' => $allCards->count(),
            'by_rarity' => [
                'common' => $ownedCollection->where('rarity', 'common')->count(),
                'rare' => $ownedCollection->where('rarity', 'rare')->count(),
                'epic' => $ownedCollection->where('rarity', 'epic')->count(),
                'legendary' => $ownedCollection->where('rarity', 'legendary')->count(),
            ],
        ];

        // Calculer le pourcentage de complétion
        $stats['completion'] = $stats['total_available'] > 0
            ? round(($stats['unique_cards'] / $stats['total_available']) * 100, 1)
            : 0;

        return view('collection.index', compact('allCards', 'stats'));
    }

    /**
     * Détail d'une carte de la collection
     */
    public function show(Card $card): View
    {
        $user = auth()->user();

        // Vérifier si le joueur possède cette carte
        $owned = $user->cards()->where('card_id', $card->id)->first();

        $card->load(['faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2']);

        return view('collection.show', compact('card', 'owned'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Affiche la collection du joueur
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Récupérer la collection avec les relations
        $collection = $user->cards()
            ->with(['faction', 'mainAttack'])
            ->orderBy('name')
            ->get();

        // Statistiques
        $stats = [
            'total_cards' => $collection->sum('pivot.quantity'),
            'unique_cards' => $collection->count(),
            'total_available' => Card::count(),
            'by_rarity' => [
                'common' => $collection->where('rarity', 'common')->count(),
                'rare' => $collection->where('rarity', 'rare')->count(),
                'epic' => $collection->where('rarity', 'epic')->count(),
                'legendary' => $collection->where('rarity', 'legendary')->count(),
            ],
        ];

        // Calculer le pourcentage de complétion
        $stats['completion'] = $stats['total_available'] > 0 
            ? round(($stats['unique_cards'] / $stats['total_available']) * 100, 1)
            : 0;

        return view('collection.index', compact('collection', 'stats'));
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
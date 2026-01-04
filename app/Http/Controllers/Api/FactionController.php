<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use Illuminate\Http\JsonResponse;

class FactionController extends Controller
{
    /**
     * Liste toutes les factions
     */
    public function index(): JsonResponse
    {
        $factions = Faction::withCount('cards')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $factions->map(function ($faction) {
                return [
                    'id' => $faction->id,
                    'name' => $faction->name,
                    'description' => $faction->description,
                    'colors' => [
                        'primary' => $faction->color_primary,
                        'secondary' => $faction->color_secondary,
                    ],
                    'image' => $faction->image ? asset('storage/' . $faction->image) : null,
                    'cards_count' => $faction->cards_count,
                ];
            }),
        ]);
    }

    /**
     * Détail d'une faction avec ses cartes
     */
    public function show(Faction $faction): JsonResponse
    {
        $faction->load('cards');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $faction->id,
                'name' => $faction->name,
                'description' => $faction->description,
                'colors' => [
                    'primary' => $faction->color_primary,
                    'secondary' => $faction->color_secondary,
                ],
                'image' => $faction->image ? asset('storage/' . $faction->image) : null,
                'cards' => $faction->cards->map(function ($card) {
                    return [
                        'id' => $card->id,
                        'name' => $card->name,
                        'grade' => $card->grade,
                        'rarity' => $card->rarity,
                    ];
                }),
            ],
        ]);
    }
}
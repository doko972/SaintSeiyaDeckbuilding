<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Liste toutes les cartes
     */
    public function index(Request $request): JsonResponse
    {
        $query = Card::with(['faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2']);

        // Filtres optionnels
        if ($request->has('faction_id')) {
            $query->where('faction_id', $request->faction_id);
        }

        if ($request->has('rarity')) {
            $query->where('rarity', $request->rarity);
        }

        if ($request->has('element')) {
            $query->where('element', $request->element);
        }

        if ($request->has('armor_type')) {
            $query->where('armor_type', $request->armor_type);
        }

        $cards = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $cards->map(function ($card) {
                return $this->formatCard($card);
            }),
            'count' => $cards->count(),
        ]);
    }

    /**
     * Détail d'une carte
     */
    public function show(Card $card): JsonResponse
    {
        $card->load(['faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2']);

        return response()->json([
            'success' => true,
            'data' => $this->formatCard($card),
        ]);
    }

    /**
     * Cartes aléatoires (pour le draft/tirage)
     */
    public function random(Request $request): JsonResponse
    {
        $count = min($request->get('count', 5), 20);

        $cards = Card::with(['faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2'])
            ->inRandomOrder()
            ->limit($count)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cards->map(function ($card) {
                return $this->formatCard($card);
            }),
        ]);
    }

    /**
     * Formate une carte pour l'API
     */
    private function formatCard(Card $card): array
    {
        return [
            'id' => $card->id,
            'name' => $card->name,
            'grade' => $card->grade,
            'armor_type' => $card->armor_type,
            'element' => $card->element,
            'rarity' => $card->rarity,
            'stats' => [
                'health_points' => $card->health_points,
                'endurance' => $card->endurance,
                'defense' => $card->defense,
                'power' => $card->power,
                'cosmos' => $card->cosmos,
                'cost' => $card->cost,
            ],
            'passive' => [
                'name' => $card->passive_ability_name,
                'description' => $card->passive_ability_description,
            ],
            'faction' => [
                'id' => $card->faction->id,
                'name' => $card->faction->name,
                'color_primary' => $card->faction->color_primary,
                'color_secondary' => $card->faction->color_secondary,
            ],
            'attacks' => [
                'main' => $this->formatAttack($card->mainAttack),
                'secondary_1' => $card->secondaryAttack1 ? $this->formatAttack($card->secondaryAttack1) : null,
                'secondary_2' => $card->secondaryAttack2 ? $this->formatAttack($card->secondaryAttack2) : null,
            ],
            'images' => [
                'primary' => $card->image_primary ? asset('storage/' . $card->image_primary) : null,
                'secondary' => $card->image_secondary ? asset('storage/' . $card->image_secondary) : null,
            ],
        ];
    }

    /**
     * Formate une attaque pour l'API
     */
    private function formatAttack($attack): array
    {
        return [
            'id' => $attack->id,
            'name' => $attack->name,
            'description' => $attack->description,
            'damage' => $attack->damage,
            'endurance_cost' => $attack->endurance_cost,
            'cosmos_cost' => $attack->cosmos_cost,
            'effect' => [
                'type' => $attack->effect_type,
                'value' => $attack->effect_value,
            ],
        ];
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    /**
     * Liste les decks de l'utilisateur connecté
     */
    public function index(Request $request): JsonResponse
    {
        $decks = $request->user()->decks()->with('cards.faction')->get();

        return response()->json([
            'success' => true,
            'data' => $decks->map(function ($deck) {
                return $this->formatDeck($deck);
            }),
        ]);
    }

    /**
     * Détail d'un deck
     */
    public function show(Request $request, Deck $deck): JsonResponse
    {
        // Vérifier que le deck appartient à l'utilisateur
        if ($deck->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        $deck->load(['cards.faction', 'cards.mainAttack', 'cards.secondaryAttack1', 'cards.secondaryAttack2']);

        return response()->json([
            'success' => true,
            'data' => $this->formatDeck($deck, true),
        ]);
    }

    /**
     * Récupère le deck actif de l'utilisateur
     */
    public function active(Request $request): JsonResponse
    {
        $deck = $request->user()->decks()
            ->where('is_active', true)
            ->with(['cards.faction', 'cards.mainAttack', 'cards.secondaryAttack1', 'cards.secondaryAttack2'])
            ->first();

        if (!$deck) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun deck actif.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatDeck($deck, true),
        ]);
    }

    /**
     * Formate un deck
     */
    private function formatDeck(Deck $deck, bool $fullCards = false): array
    {
        $data = [
            'id' => $deck->id,
            'name' => $deck->name,
            'description' => $deck->description,
            'is_active' => $deck->is_active,
            'total_cards' => $deck->cards->sum('pivot.quantity'),
            'created_at' => $deck->created_at->toISOString(),
        ];

        if ($fullCards) {
            $data['cards'] = $deck->cards->map(function ($card) {
                return [
                    'id' => $card->id,
                    'name' => $card->name,
                    'quantity' => $card->pivot->quantity,
                    'grade' => $card->grade,
                    'rarity' => $card->rarity,
                    'element' => $card->element,
                    'armor_type' => $card->armor_type,
                    'stats' => [
                        'health_points' => $card->health_points,
                        'endurance' => $card->endurance,
                        'defense' => $card->defense,
                        'power' => $card->power,
                        'cosmos' => $card->cosmos,
                        'cost' => $card->cost,
                    ],
                    'faction' => [
                        'id' => $card->faction->id,
                        'name' => $card->faction->name,
                        'color_primary' => $card->faction->color_primary,
                    ],
                    'attacks' => [
                        'main' => [
                            'id' => $card->mainAttack->id,
                            'name' => $card->mainAttack->name,
                            'damage' => $card->mainAttack->damage,
                            'endurance_cost' => $card->mainAttack->endurance_cost,
                            'cosmos_cost' => $card->mainAttack->cosmos_cost,
                            'effect_type' => $card->mainAttack->effect_type,
                            'effect_value' => $card->mainAttack->effect_value,
                        ],
                        'secondary_1' => $card->secondaryAttack1 ? [
                            'id' => $card->secondaryAttack1->id,
                            'name' => $card->secondaryAttack1->name,
                            'damage' => $card->secondaryAttack1->damage,
                            'endurance_cost' => $card->secondaryAttack1->endurance_cost,
                            'cosmos_cost' => $card->secondaryAttack1->cosmos_cost,
                            'effect_type' => $card->secondaryAttack1->effect_type,
                            'effect_value' => $card->secondaryAttack1->effect_value,
                        ] : null,
                        'secondary_2' => $card->secondaryAttack2 ? [
                            'id' => $card->secondaryAttack2->id,
                            'name' => $card->secondaryAttack2->name,
                            'damage' => $card->secondaryAttack2->damage,
                            'endurance_cost' => $card->secondaryAttack2->endurance_cost,
                            'cosmos_cost' => $card->secondaryAttack2->cosmos_cost,
                            'effect_type' => $card->secondaryAttack2->effect_type,
                            'effect_value' => $card->secondaryAttack2->effect_value,
                        ] : null,
                    ],
                    'image' => $card->image_primary ? asset('storage/' . $card->image_primary) : null,
                ];
            });
        } else {
            $data['cards_preview'] = $deck->cards->take(5)->map(function ($card) {
                return [
                    'id' => $card->id,
                    'name' => $card->name,
                    'quantity' => $card->pivot->quantity,
                ];
            });
        }

        return $data;
    }
}
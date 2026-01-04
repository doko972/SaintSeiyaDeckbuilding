<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attack;
use Illuminate\Http\JsonResponse;

class AttackController extends Controller
{
    /**
     * Liste toutes les attaques
     */
    public function index(): JsonResponse
    {
        $attacks = Attack::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $attacks->map(function ($attack) {
                return $this->formatAttack($attack);
            }),
        ]);
    }

    /**
     * Détail d'une attaque
     */
    public function show(Attack $attack): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->formatAttack($attack),
        ]);
    }

    /**
     * Formate une attaque
     */
    private function formatAttack(Attack $attack): array
    {
        return [
            'id' => $attack->id,
            'name' => $attack->name,
            'description' => $attack->description,
            'damage' => $attack->damage,
            'costs' => [
                'endurance' => $attack->endurance_cost,
                'cosmos' => $attack->cosmos_cost,
            ],
            'effect' => [
                'type' => $attack->effect_type,
                'value' => $attack->effect_value,
            ],
        ];
    }
}
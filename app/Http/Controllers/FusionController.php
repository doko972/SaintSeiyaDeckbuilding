<?php

namespace App\Http\Controllers;

use App\Services\FusionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FusionController extends Controller
{
    protected FusionService $fusionService;

    public function __construct(FusionService $fusionService)
    {
        $this->fusionService = $fusionService;
    }

    /**
     * Page de fusion - liste les cartes fusionnables
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $fusionableCards = $this->fusionService->getFusionableCards($user);
        $upgradedCards = $this->fusionService->getUpgradedCards($user);

        // Calculer les stats boostées pour chaque carte améliorée
        $upgradedCards = $upgradedCards->map(function ($card) {
            $card->boosted_stats = $this->fusionService->calculateBoostedStats(
                $card,
                $card->pivot->fusion_level
            );
            return $card;
        });

        return view('fusion.index', [
            'fusionableCards' => $fusionableCards,
            'upgradedCards' => $upgradedCards,
            'userCoins' => $user->coins,
            'fusionCosts' => FusionService::FUSION_COSTS,
            'maxLevel' => FusionService::MAX_LEVEL,
        ]);
    }

    /**
     * Aperçu d'une fusion (AJAX)
     */
    public function preview(Request $request): JsonResponse
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $preview = $this->fusionService->getFusionPreview($user, $request->card_id);

        return response()->json($preview);
    }

    /**
     * Exécuter la fusion
     */
    public function fuse(Request $request): JsonResponse
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $result = $this->fusionService->performFusion($user, $request->card_id);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['reason'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fusion réussie !',
            'new_level' => $result['new_level'],
            'stats' => $result['stats'],
            'cost_paid' => $result['cost_paid'],
            'new_balance' => $result['new_balance'],
        ]);
    }
}

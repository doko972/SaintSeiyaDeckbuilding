<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class DailyBonusController extends Controller
{
    /**
     * Vérifie si le joueur peut récupérer son bonus
     */
    public function check(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $canClaim = $user->canClaimDailyBonus();
        $timeUntilNext = $user->getTimeUntilNextBonus();

        return response()->json([
            'can_claim' => $canClaim,
            'hours_left' => $timeUntilNext['hours'],
            'minutes_left' => $timeUntilNext['minutes'],
        ]);
    }

    /**
     * Réclame le bonus quotidien
     */
    public function claim(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $result = $user->claimDailyBonus();

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'dice_result' => $result['dice_result'],
            'coins_earned' => $result['coins_earned'],
            'new_balance' => $user->fresh()->coins,
            'message' => $result['message'],
        ]);
    }
}

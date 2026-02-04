<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RewardsController extends Controller
{
    /**
     * Page des recompenses (serie de connexion + roue)
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Mettre a jour le streak a chaque visite
        $user->updateLoginStreak();

        return view('rewards.index', [
            'user' => $user,
            'streakInfo' => $user->getStreakInfo(),
            'canSpinWheel' => $user->canSpinWheel(),
            'timeUntilSpin' => $user->getTimeUntilNextSpin(),
        ]);
    }

    /**
     * Verifie le statut du streak (AJAX)
     */
    public function checkStreak(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Mettre a jour le streak
        $user->updateLoginStreak();

        return response()->json([
            'streak_info' => $user->getStreakInfo(),
        ]);
    }

    /**
     * Reclame la recompense de streak (AJAX)
     */
    public function claimStreak(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $result = $user->claimStreakReward();

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'day' => $result['day'],
            'coins_earned' => $result['coins_earned'],
            'card' => $result['card'] ? [
                'id' => $result['card']->id,
                'name' => $result['card']->name,
                'rarity' => $result['card']->rarity,
                'image' => $result['card']->image_primary,
            ] : null,
            'new_balance' => $user->fresh()->coins,
            'message' => $result['message'],
        ]);
    }

    /**
     * Verifie si la roue peut etre tournee (AJAX)
     */
    public function checkWheel(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return response()->json([
            'can_spin' => $user->canSpinWheel(),
            'time_until_spin' => $user->getTimeUntilNextSpin(),
        ]);
    }

    /**
     * Tourne la roue de la fortune (AJAX)
     */
    public function spinWheel(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $result = $user->spinWheel();

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        // Preparer les donnees de la carte si gagnee
        $cardData = null;
        if (isset($result['reward']['card']) && $result['reward']['card']) {
            $card = $result['reward']['card'];
            $cardData = [
                'id' => $card->id,
                'name' => $card->name,
                'rarity' => $card->rarity,
                'image' => $card->image_primary,
            ];
        }

        // Preparer les donnees des cartes du booster si gagne
        $boosterCards = null;
        if (isset($result['reward']['cards']) && $result['reward']['cards']) {
            $boosterCards = collect($result['reward']['cards'])->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'rarity' => $c->rarity,
            ])->toArray();
        }

        return response()->json([
            'success' => true,
            'segment_index' => $result['segment_index'],
            'segment' => $result['segment'],
            'reward_type' => $result['reward']['type'],
            'reward_value' => $result['reward']['value'] ?? null,
            'reward_card' => $cardData,
            'reward_booster_cards' => $boosterCards,
            'new_balance' => $user->fresh()->coins,
            'message' => $result['message'],
            'segments' => $result['segments'],
        ]);
    }
}

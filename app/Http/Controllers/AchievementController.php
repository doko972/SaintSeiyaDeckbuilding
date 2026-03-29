<?php

namespace App\Http\Controllers;

use App\Services\AchievementService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function __construct(private AchievementService $achievementService) {}

    public function index(): View
    {
        $user         = auth()->user();
        $achievements = $this->achievementService->getAllForUser($user);
        $unclaimedCount = collect($achievements)
            ->filter(fn($a) => $a['unlocked'] && !$a['reward_claimed'])
            ->count();

        return view('achievements.index', compact('achievements', 'unclaimedCount'));
    }

    public function claim(int $achievementId): JsonResponse
    {
        $result = $this->achievementService->claimReward(auth()->user(), $achievementId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}

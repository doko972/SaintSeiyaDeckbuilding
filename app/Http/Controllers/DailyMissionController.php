<?php

namespace App\Http\Controllers;

use App\Services\DailyMissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyMissionController extends Controller
{
    public function __construct(private DailyMissionService $missionService) {}

    /**
     * Réclame la récompense d'une mission complétée (AJAX).
     */
    public function claim(Request $request, int $missionId): JsonResponse
    {
        $result = $this->missionService->claimReward(auth()->user(), $missionId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}

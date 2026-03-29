<?php

namespace App\Services;

use App\Models\DailyMission;
use App\Models\User;
use Illuminate\Support\Collection;

class DailyMissionService
{
    /**
     * Retourne (et crée si besoin) les 3 missions du jour pour l'utilisateur.
     */
    public function getTodayMissions(User $user): Collection
    {
        $today = today()->toDateString();

        foreach (array_keys(DailyMission::MISSIONS) as $type) {
            DailyMission::firstOrCreate([
                'user_id'      => $user->id,
                'mission_date' => $today,
                'type'         => $type,
            ]);
        }

        return DailyMission::where('user_id', $user->id)
            ->where('mission_date', $today)
            ->get()
            ->keyBy('type');
    }

    /**
     * Marque une mission du jour comme complétée (une seule fois par jour).
     */
    public function complete(User $user, string $type): bool
    {
        $mission = DailyMission::where('user_id', $user->id)
            ->where('mission_date', today()->toDateString())
            ->where('type', $type)
            ->whereNull('completed_at')
            ->first();

        if ($mission) {
            $mission->update(['completed_at' => now()]);
            return true;
        }

        return false;
    }

    /**
     * Réclame la récompense d'une mission complétée.
     */
    public function claimReward(User $user, int $missionId): array
    {
        $mission = DailyMission::where('id', $missionId)
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereNull('reward_claimed_at')
            ->first();

        if (!$mission) {
            return ['success' => false, 'message' => 'Mission introuvable ou récompense déjà réclamée.'];
        }

        $config = $mission->getConfig();
        $coins  = $config['reward_coins'] ?? 0;

        $user->addCoins($coins);
        $mission->update(['reward_claimed_at' => now()]);

        return [
            'success'     => true,
            'coins'       => $coins,
            'new_balance' => $user->fresh()->coins,
        ];
    }
}

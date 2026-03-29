<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Collection;

class AchievementService
{
    const RANK_ORDER = ['bronze' => 0, 'silver' => 1, 'gold' => 2, 'divine' => 3];

    /**
     * Vérifie et débloque tous les succès non encore obtenus par l'utilisateur.
     * Retourne les nouveaux succès débloqués.
     */
    public function checkAndUnlock(User $user): Collection
    {
        $unlocked = UserAchievement::where('user_id', $user->id)
            ->pluck('slug')
            ->flip();

        // Données pré-chargées pour éviter les N+1
        $uniqueCardCount  = $user->cards()->count();
        $cardsByRarity    = $user->cards()->pluck('rarity')->countBy();
        $userRankOrder    = self::RANK_ORDER[strtolower($user->current_rank ?? 'bronze')] ?? 0;
        $factionComplete  = $this->hasFactionComplete($user);

        $newlyUnlocked = collect();

        foreach (UserAchievement::ACHIEVEMENTS as $slug => $config) {
            if ($unlocked->has($slug)) continue;

            if ($this->checkCondition($user, $config, $uniqueCardCount, $cardsByRarity, $userRankOrder, $factionComplete)) {
                UserAchievement::create([
                    'user_id'    => $user->id,
                    'slug'       => $slug,
                    'unlocked_at' => now(),
                ]);
                $newlyUnlocked->push(array_merge(['slug' => $slug], $config));
            }
        }

        return $newlyUnlocked;
    }

    /**
     * Réclame la récompense d'un succès débloqué.
     */
    public function claimReward(User $user, int $achievementId): array
    {
        $achievement = UserAchievement::where('id', $achievementId)
            ->where('user_id', $user->id)
            ->whereNull('reward_claimed_at')
            ->first();

        if (!$achievement) {
            return ['success' => false, 'message' => 'Succès introuvable ou récompense déjà réclamée.'];
        }

        $config = $achievement->getConfig();
        $coins  = $config['reward_coins'] ?? 0;

        $user->addCoins($coins);
        $achievement->update(['reward_claimed_at' => now()]);

        return [
            'success'     => true,
            'coins'       => $coins,
            'new_balance' => $user->fresh()->coins,
        ];
    }

    /**
     * Retourne tous les succès avec leur statut pour l'utilisateur.
     */
    public function getAllForUser(User $user): array
    {
        $userAchievements = UserAchievement::where('user_id', $user->id)
            ->get()
            ->keyBy('slug');

        $result = [];
        foreach (UserAchievement::ACHIEVEMENTS as $slug => $config) {
            $ua = $userAchievements->get($slug);
            $result[] = array_merge($config, [
                'slug'               => $slug,
                'unlocked'           => !is_null($ua),
                'unlocked_at'        => $ua?->unlocked_at,
                'reward_claimed'     => $ua?->isRewardClaimed() ?? false,
                'achievement_id'     => $ua?->id,
            ]);
        }

        return $result;
    }

    // ── Conditions ───────────────────────────────────────────────────────────

    private function checkCondition(
        User $user,
        array $config,
        int $uniqueCardCount,
        Collection $cardsByRarity,
        int $userRankOrder,
        bool $factionComplete
    ): bool {
        return match($config['condition_type']) {
            'wins'           => $user->wins >= $config['condition_value'],
            'pvp_wins'       => $user->pvp_wins >= $config['condition_value'],
            'pve_wins'       => $user->pve_wins >= $config['condition_value'],
            'unique_cards'   => $uniqueCardCount >= $config['condition_value'],
            'rarity_owned'   => ($cardsByRarity[$config['condition_value']] ?? 0) >= 1,
            'faction_complete' => $factionComplete,
            'login_streak'   => $user->login_streak >= $config['condition_value'],
            'rank'           => $userRankOrder >= (self::RANK_ORDER[$config['condition_value']] ?? 99),
            default          => false,
        };
    }

    private function hasFactionComplete(User $user): bool
    {
        $ownedIds = $user->cards()->pluck('cards.id')->flip();

        foreach (\App\Models\Faction::withCount('cards')->get() as $faction) {
            if ($faction->cards_count === 0) continue;

            $allOwned = $faction->cards()->pluck('id')->every(fn($id) => $ownedIds->has($id));
            if ($allOwned) return true;
        }

        return false;
    }
}

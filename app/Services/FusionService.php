<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FusionService
{
    /**
     * Bonus par niveau (cumulatif) - pourcentage de base
     */
    public const LEVEL_BONUSES = [
        2 => 10,
        3 => 8,
        4 => 7,
        5 => 6,
        6 => 5,
        7 => 4,
        8 => 3,
        9 => 3,
        10 => 2,
    ];

    /**
     * Facteurs de rareté (multiplicateur du bonus)
     */
    public const RARITY_FACTORS = [
        'common' => 0.3,
        'rare' => 0.5,
        'epic' => 0.6,
        'legendary' => 0.7,
        'mythic' => 1.0,
    ];

    /**
     * Coûts en pièces par fusion selon la rareté
     */
    public const FUSION_COSTS = [
        'common' => 100,
        'rare' => 250,
        'epic' => 350,
        'legendary' => 500,
        'mythic' => 1000,
    ];

    /**
     * Niveau maximum
     */
    public const MAX_LEVEL = 10;

    /**
     * Stats affectées par la fusion
     */
    public const AFFECTED_STATS = ['health_points', 'endurance', 'defense', 'power'];

    /**
     * Calcule le bonus cumulé pour un niveau donné
     *
     * @param int $level Niveau actuel (1-10)
     * @param string $rarity Rareté de la carte
     * @return float Pourcentage de bonus total
     */
    public function calculateCumulativeBonus(int $level, string $rarity): float
    {
        if ($level <= 1) {
            return 0;
        }

        $totalBonus = 0;
        for ($i = 2; $i <= $level; $i++) {
            $totalBonus += self::LEVEL_BONUSES[$i] ?? 0;
        }

        $rarityFactor = self::RARITY_FACTORS[$rarity] ?? 0.3;

        return $totalBonus * $rarityFactor;
    }

    /**
     * Calcule les stats boostées d'une carte
     *
     * @param Card $card La carte de base
     * @param int $fusionLevel Le niveau de fusion
     * @return array Stats modifiées
     */
    public function calculateBoostedStats(Card $card, int $fusionLevel): array
    {
        $bonus = $this->calculateCumulativeBonus($fusionLevel, $card->rarity);
        $multiplier = 1 + ($bonus / 100);

        return [
            'health_points' => (int) round($card->health_points * $multiplier),
            'endurance' => (int) round($card->endurance * $multiplier),
            'defense' => (int) round($card->defense * $multiplier),
            'power' => (int) round($card->power * $multiplier),
            'cosmos' => $card->cosmos, // Non affecté
            'bonus_percent' => round($bonus, 1),
        ];
    }

    /**
     * Calcule les stats boostées à partir d'un tableau de stats de base
     *
     * @param array $baseStats Stats de base [health_points, endurance, defense, power, cosmos]
     * @param int $fusionLevel Le niveau de fusion
     * @param string $rarity La rareté de la carte
     * @return array Stats modifiées
     */
    public function calculateBoostedStatsFromArray(array $baseStats, int $fusionLevel, string $rarity): array
    {
        $bonus = $this->calculateCumulativeBonus($fusionLevel, $rarity);
        $multiplier = 1 + ($bonus / 100);

        return [
            'health_points' => (int) round(($baseStats['health_points'] ?? 100) * $multiplier),
            'endurance' => (int) round(($baseStats['endurance'] ?? 100) * $multiplier),
            'defense' => (int) round(($baseStats['defense'] ?? 0) * $multiplier),
            'power' => (int) round(($baseStats['power'] ?? 0) * $multiplier),
            'cosmos' => $baseStats['cosmos'] ?? 0, // Non affecté
            'bonus_percent' => round($bonus, 1),
        ];
    }

    /**
     * Vérifie si une fusion est possible
     *
     * @param User $user
     * @param int $cardId
     * @return array ['can_fuse' => bool, 'reason' => string|null, 'cost' => int]
     */
    public function canFuse(User $user, int $cardId): array
    {
        $userCard = $user->cards()->where('card_id', $cardId)->first();

        if (!$userCard) {
            return [
                'can_fuse' => false,
                'reason' => 'Vous ne possédez pas cette carte.',
                'cost' => 0,
            ];
        }

        $currentLevel = $userCard->pivot->fusion_level ?? 1;
        $quantity = $userCard->pivot->quantity ?? 1;

        // Vérifier le niveau max
        if ($currentLevel >= self::MAX_LEVEL) {
            return [
                'can_fuse' => false,
                'reason' => 'Cette carte a atteint le niveau maximum.',
                'cost' => 0,
            ];
        }

        // Vérifier les doublons disponibles
        if ($quantity < 2) {
            return [
                'can_fuse' => false,
                'reason' => 'Vous avez besoin d\'au moins un doublon pour fusionner.',
                'cost' => 0,
            ];
        }

        // Calculer le coût
        $cost = self::FUSION_COSTS[$userCard->rarity] ?? 100;

        // Vérifier les pièces
        if ($user->coins < $cost) {
            return [
                'can_fuse' => false,
                'reason' => "Vous n'avez pas assez de pièces. Coût: {$cost} po.",
                'cost' => $cost,
            ];
        }

        return [
            'can_fuse' => true,
            'reason' => null,
            'cost' => $cost,
        ];
    }

    /**
     * Exécute la fusion
     *
     * @param User $user
     * @param int $cardId
     * @return array ['success' => bool, 'new_level' => int, 'stats' => array, 'reason' => string|null]
     */
    public function performFusion(User $user, int $cardId): array
    {
        $canFuse = $this->canFuse($user, $cardId);

        if (!$canFuse['can_fuse']) {
            return [
                'success' => false,
                'reason' => $canFuse['reason'],
                'new_level' => null,
                'stats' => null,
            ];
        }

        $card = Card::findOrFail($cardId);
        $userCard = $user->cards()->where('card_id', $cardId)->first();
        $currentLevel = $userCard->pivot->fusion_level ?? 1;
        $newLevel = $currentLevel + 1;
        $cost = $canFuse['cost'];

        // Transaction pour garantir l'intégrité
        DB::transaction(function () use ($user, $cardId, $newLevel, $cost) {
            // Déduire les pièces
            $user->decrement('coins', $cost);

            // Réduire la quantité et augmenter le niveau
            $user->cards()->updateExistingPivot($cardId, [
                'quantity' => DB::raw('quantity - 1'),
                'fusion_level' => $newLevel,
            ]);
        });

        // Rafraîchir les données
        $user->refresh();
        $newStats = $this->calculateBoostedStats($card, $newLevel);

        return [
            'success' => true,
            'reason' => null,
            'new_level' => $newLevel,
            'stats' => $newStats,
            'cost_paid' => $cost,
            'new_balance' => $user->coins,
        ];
    }

    /**
     * Aperçu de la fusion (stats avant/après)
     *
     * @param User $user
     * @param int $cardId
     * @return array Preview complet
     */
    public function getFusionPreview(User $user, int $cardId): array
    {
        $card = Card::with('faction')->findOrFail($cardId);
        $userCard = $user->cards()->where('card_id', $cardId)->first();

        if (!$userCard) {
            return ['error' => 'Carte non trouvée dans votre collection'];
        }

        $currentLevel = $userCard->pivot->fusion_level ?? 1;
        $quantity = $userCard->pivot->quantity ?? 1;
        $nextLevel = min($currentLevel + 1, self::MAX_LEVEL);

        $currentStats = $this->calculateBoostedStats($card, $currentLevel);
        $nextStats = $this->calculateBoostedStats($card, $nextLevel);

        $canFuse = $this->canFuse($user, $cardId);

        return [
            'card' => [
                'id' => $card->id,
                'name' => $card->name,
                'rarity' => $card->rarity,
                'image' => $card->image_primary,
                'faction' => $card->faction?->name,
            ],
            'current_level' => $currentLevel,
            'next_level' => $nextLevel,
            'max_level' => self::MAX_LEVEL,
            'quantity' => $quantity,
            'doublons_disponibles' => max(0, $quantity - 1),
            'current_stats' => $currentStats,
            'next_stats' => $nextStats,
            'stat_gains' => [
                'health_points' => $nextStats['health_points'] - $currentStats['health_points'],
                'endurance' => $nextStats['endurance'] - $currentStats['endurance'],
                'defense' => $nextStats['defense'] - $currentStats['defense'],
                'power' => $nextStats['power'] - $currentStats['power'],
            ],
            'cost' => $canFuse['cost'],
            'can_fuse' => $canFuse['can_fuse'],
            'reason' => $canFuse['reason'],
            'user_coins' => $user->coins,
        ];
    }

    /**
     * Récupère toutes les cartes fusionnables d'un utilisateur
     * (cartes avec au moins 2 exemplaires ET niveau < 10)
     *
     * @param User $user
     * @return Collection
     */
    public function getFusionableCards(User $user): Collection
    {
        return $user->cards()
            ->with(['faction', 'mainAttack'])
            ->wherePivot('quantity', '>=', 2)
            ->wherePivot('fusion_level', '<', self::MAX_LEVEL)
            ->orderBy('rarity', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Récupère toutes les cartes améliorées d'un utilisateur (niveau > 1)
     *
     * @param User $user
     * @return Collection
     */
    public function getUpgradedCards(User $user): Collection
    {
        return $user->cards()
            ->with(['faction', 'mainAttack'])
            ->wherePivot('fusion_level', '>', 1)
            ->orderBy('rarity', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Obtient le niveau de fusion d'une carte pour un utilisateur
     *
     * @param User $user
     * @param int $cardId
     * @return int
     */
    public function getCardFusionLevel(User $user, int $cardId): int
    {
        $userCard = $user->cards()->where('card_id', $cardId)->first();
        return $userCard?->pivot?->fusion_level ?? 1;
    }
}

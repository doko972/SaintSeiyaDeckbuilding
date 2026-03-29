<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'unlocked_at',
        'reward_claimed_at',
    ];

    protected $casts = [
        'unlocked_at'       => 'datetime',
        'reward_claimed_at' => 'datetime',
    ];

    // ── Définition de tous les succès ────────────────────────────────────────

    const ACHIEVEMENTS = [
        // Victoires
        'first_win' => [
            'title'           => 'Première victoire',
            'description'     => 'Remporter 1 combat',
            'icon'            => '⚔️',
            'reward_coins'    => 100,
            'condition_type'  => 'wins',
            'condition_value' => 1,
            'category'        => 'combat',
        ],
        'wins_10' => [
            'title'           => 'Vétéran',
            'description'     => 'Remporter 10 combats',
            'icon'            => '🗡️',
            'reward_coins'    => 250,
            'condition_type'  => 'wins',
            'condition_value' => 10,
            'category'        => 'combat',
        ],
        'wins_50' => [
            'title'           => 'Guerrier',
            'description'     => 'Remporter 50 combats',
            'icon'            => '🏆',
            'reward_coins'    => 500,
            'condition_type'  => 'wins',
            'condition_value' => 50,
            'category'        => 'combat',
        ],
        'wins_100' => [
            'title'           => 'Légende',
            'description'     => 'Remporter 100 combats',
            'icon'            => '👑',
            'reward_coins'    => 1000,
            'condition_type'  => 'wins',
            'condition_value' => 100,
            'category'        => 'combat',
        ],
        'first_pvp' => [
            'title'           => 'Premier duel',
            'description'     => 'Remporter 1 combat PvP',
            'icon'            => '🤺',
            'reward_coins'    => 150,
            'condition_type'  => 'pvp_wins',
            'condition_value' => 1,
            'category'        => 'combat',
        ],

        // Collection
        'cards_10' => [
            'title'           => 'Débutant',
            'description'     => 'Posséder 10 cartes uniques',
            'icon'            => '📚',
            'reward_coins'    => 150,
            'condition_type'  => 'unique_cards',
            'condition_value' => 10,
            'category'        => 'collection',
        ],
        'cards_50' => [
            'title'           => 'Collectionneur',
            'description'     => 'Posséder 50 cartes uniques',
            'icon'            => '📖',
            'reward_coins'    => 500,
            'condition_type'  => 'unique_cards',
            'condition_value' => 50,
            'category'        => 'collection',
        ],
        'own_epic' => [
            'title'           => 'Première Épique',
            'description'     => 'Posséder une carte épique',
            'icon'            => '💎',
            'reward_coins'    => 200,
            'condition_type'  => 'rarity_owned',
            'condition_value' => 'epic',
            'category'        => 'collection',
        ],
        'own_legendary' => [
            'title'           => 'Première Légendaire',
            'description'     => 'Posséder une carte légendaire',
            'icon'            => '⭐',
            'reward_coins'    => 500,
            'condition_type'  => 'rarity_owned',
            'condition_value' => 'legendary',
            'category'        => 'collection',
        ],
        'own_mythic' => [
            'title'           => 'Première Mythique',
            'description'     => 'Posséder une carte mythique',
            'icon'            => '🌟',
            'reward_coins'    => 1000,
            'condition_type'  => 'rarity_owned',
            'condition_value' => 'mythic',
            'category'        => 'collection',
        ],
        'faction_complete' => [
            'title'           => 'Maître de faction',
            'description'     => 'Compléter la collection d\'une faction',
            'icon'            => '🏰',
            'reward_coins'    => 1000,
            'condition_type'  => 'faction_complete',
            'condition_value' => 1,
            'category'        => 'collection',
        ],

        // Fidélité
        'streak_7' => [
            'title'           => 'Fidèle',
            'description'     => 'Se connecter 7 jours d\'affilée',
            'icon'            => '🔥',
            'reward_coins'    => 500,
            'condition_type'  => 'login_streak',
            'condition_value' => 7,
            'category'        => 'fidelite',
        ],

        // Rang
        'rank_silver' => [
            'title'           => 'Rang Argent',
            'description'     => 'Atteindre le rang Argent',
            'icon'            => '🥈',
            'reward_coins'    => 300,
            'condition_type'  => 'rank',
            'condition_value' => 'silver',
            'category'        => 'rang',
        ],
        'rank_gold' => [
            'title'           => 'Rang Or',
            'description'     => 'Atteindre le rang Or',
            'icon'            => '🥇',
            'reward_coins'    => 700,
            'condition_type'  => 'rank',
            'condition_value' => 'gold',
            'category'        => 'rang',
        ],
        'rank_divine' => [
            'title'           => 'Rang Divin',
            'description'     => 'Atteindre le rang Divin',
            'icon'            => '✨',
            'reward_coins'    => 1500,
            'condition_type'  => 'rank',
            'condition_value' => 'divine',
            'category'        => 'rang',
        ],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getConfig(): array
    {
        return self::ACHIEVEMENTS[$this->slug] ?? [];
    }

    public function isRewardClaimed(): bool
    {
        return !is_null($this->reward_claimed_at);
    }
}

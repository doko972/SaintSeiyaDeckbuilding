<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyMission extends Model
{
    protected $fillable = [
        'user_id',
        'mission_date',
        'type',
        'completed_at',
        'reward_claimed_at',
    ];

    protected $casts = [
        'mission_date'      => 'date',
        'completed_at'      => 'datetime',
        'reward_claimed_at' => 'datetime',
    ];

    const MISSIONS = [
        'combat_win' => [
            'title'        => 'Guerrier du Jour',
            'description'  => 'Remporter 1 combat (PvE ou PvP)',
            'icon'         => '⚔️',
            'reward_coins' => 200,
        ],
        'buy_booster' => [
            'title'        => 'Collectionneur',
            'description'  => 'Acheter 1 booster',
            'icon'         => '📦',
            'reward_coins' => 150,
        ],
        'fusion' => [
            'title'        => 'Alchimiste',
            'description'  => 'Fusionner 1 carte',
            'icon'         => '✨',
            'reward_coins' => 150,
        ],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    public function isRewardClaimed(): bool
    {
        return !is_null($this->reward_claimed_at);
    }

    public function getConfig(): array
    {
        return self::MISSIONS[$this->type] ?? [];
    }
}

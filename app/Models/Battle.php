<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_id',
        'player2_id',
        'player1_deck_id',
        'player2_deck_id',
        'battle_state',
        'status',
        'winner_id',
        'current_turn_user_id',
        'turn_number',
        'last_action_at',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'battle_state' => 'array',
        'last_action_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    // Relations
    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function player1Deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class, 'player1_deck_id');
    }

    public function player2Deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class, 'player2_deck_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function currentTurnUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_turn_user_id');
    }

    // Helpers
    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function isPlayerTurn(User $user): bool
    {
        return $this->current_turn_user_id === $user->id;
    }

    public function getOpponent(User $user): ?User
    {
        if ($this->player1_id === $user->id) {
            return $this->player2;
        }
        return $this->player1;
    }

    public function getPlayerNumber(User $user): ?int
    {
        if ($this->player1_id === $user->id) return 1;
        if ($this->player2_id === $user->id) return 2;
        return null;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'user_id',
        'deck_id',
        'seed_position',
        'bracket_position',
        'status',
        'final_rank',
        'wins',
        'losses',
        'registered_at',
        'eliminated_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'eliminated_at' => 'datetime',
    ];

    const STATUS_REGISTERED = 'registered';
    const STATUS_ACTIVE = 'active';
    const STATUS_ELIMINATED = 'eliminated';
    const STATUS_WINNER = 'winner';
    const STATUS_DISQUALIFIED = 'disqualified';

    // Relations
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    public function matchesAsParticipant1(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'participant1_id');
    }

    public function matchesAsParticipant2(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'participant2_id');
    }

    // Helpers
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_REGISTERED, self::STATUS_ACTIVE]);
    }

    public function isEliminated(): bool
    {
        return $this->status === self::STATUS_ELIMINATED;
    }

    public function isWinner(): bool
    {
        return $this->status === self::STATUS_WINNER;
    }

    public function getCurrentMatch(): ?TournamentMatch
    {
        return TournamentMatch::where('tournament_id', $this->tournament_id)
            ->where(function ($q) {
                $q->where('participant1_id', $this->id)
                    ->orWhere('participant2_id', $this->id);
            })
            ->whereIn('status', ['ready', 'in_progress'])
            ->first();
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_REGISTERED => 'Inscrit',
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_ELIMINATED => 'Elimine',
            self::STATUS_WINNER => 'Vainqueur',
            self::STATUS_DISQUALIFIED => 'Disqualifie',
            default => $this->status,
        };
    }
}

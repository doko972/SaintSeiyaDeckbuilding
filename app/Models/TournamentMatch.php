<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'round',
        'match_number',
        'bracket_code',
        'participant1_id',
        'participant2_id',
        'battle_id',
        'winner_participant_id',
        'status',
        'scheduled_at',
        'started_at',
        'finished_at',
        'next_match_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_READY = 'ready';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_FINISHED = 'finished';
    const STATUS_BYE = 'bye';

    // Relations
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function participant1(): BelongsTo
    {
        return $this->belongsTo(TournamentParticipant::class, 'participant1_id');
    }

    public function participant2(): BelongsTo
    {
        return $this->belongsTo(TournamentParticipant::class, 'participant2_id');
    }

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(TournamentParticipant::class, 'winner_participant_id');
    }

    public function nextMatch(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'next_match_id');
    }

    // Helpers
    public function isReady(): bool
    {
        return $this->participant1_id && $this->participant2_id && $this->status === self::STATUS_READY;
    }

    public function isBye(): bool
    {
        return $this->status === self::STATUS_BYE;
    }

    public function isFinished(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function getLoser(): ?TournamentParticipant
    {
        if (!$this->winner_participant_id) {
            return null;
        }

        return $this->winner_participant_id === $this->participant1_id
            ? $this->participant2
            : $this->participant1;
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_READY => 'Pret',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_FINISHED => 'Termine',
            self::STATUS_BYE => 'Bye',
            default => $this->status,
        };
    }

    public function involvesUser(User $user): bool
    {
        return ($this->participant1 && $this->participant1->user_id === $user->id)
            || ($this->participant2 && $this->participant2->user_id === $user->id);
    }
}

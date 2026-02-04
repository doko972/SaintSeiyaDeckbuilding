<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'max_players',
        'current_round',
        'total_rounds',
        'registration_start_at',
        'registration_end_at',
        'start_at',
        'finished_at',
        'rewards_config',
        'min_deck_cards',
        'entry_fee',
        'is_featured',
    ];

    protected $casts = [
        'rewards_config' => 'array',
        'registration_start_at' => 'datetime',
        'registration_end_at' => 'datetime',
        'start_at' => 'datetime',
        'finished_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_REGISTRATION = 'registration';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_FINISHED = 'finished';
    const STATUS_CANCELLED = 'cancelled';

    const DEFAULT_REWARDS = [
        'coins_multiplier' => 3,
        'winner_coins' => 5000,
        'runner_up_coins' => 2500,
        'semifinalist_coins' => 1000,
        'participation_coins' => 200,
        'exclusive_card_id' => null,
        'winner_title' => null,
    ];

    // Relations
    public function participants(): HasMany
    {
        return $this->hasMany(TournamentParticipant::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class);
    }

    public function activeParticipants(): HasMany
    {
        return $this->participants()->whereIn('status', ['registered', 'active']);
    }

    // Helpers
    public function isRegistrationOpen(): bool
    {
        if ($this->status !== self::STATUS_REGISTRATION) {
            return false;
        }

        // Si une date de fin est definie et depassee, les inscriptions sont fermees
        if ($this->registration_end_at && now()->greaterThan($this->registration_end_at)) {
            return false;
        }

        return true;
    }

    public function canJoin(User $user): array
    {
        if ($this->status !== self::STATUS_REGISTRATION) {
            return ['can_join' => false, 'reason' => 'Les inscriptions ne sont pas ouvertes.'];
        }

        if (!$this->isRegistrationOpen()) {
            return ['can_join' => false, 'reason' => 'La periode d\'inscription est terminee.'];
        }

        if ($this->participants()->count() >= $this->max_players) {
            return ['can_join' => false, 'reason' => 'Le tournoi est complet.'];
        }

        if ($this->participants()->where('user_id', $user->id)->exists()) {
            return ['can_join' => false, 'reason' => 'Vous etes deja inscrit.'];
        }

        if ($this->entry_fee > 0 && $user->coins < $this->entry_fee) {
            return ['can_join' => false, 'reason' => 'Vous n\'avez pas assez de pieces.'];
        }

        return ['can_join' => true, 'reason' => null];
    }

    public function getRewardsConfig(): array
    {
        return array_merge(self::DEFAULT_REWARDS, $this->rewards_config ?? []);
    }

    public function getRoundName(int $round): string
    {
        $remaining = $this->total_rounds - $round + 1;
        return match ($remaining) {
            1 => 'Finale',
            2 => 'Demi-finales',
            3 => 'Quarts de finale',
            4 => 'Huitiemes de finale',
            default => "Tour $round"
        };
    }

    public function getParticipantCount(): int
    {
        return $this->participants()->count();
    }

    public function isFull(): bool
    {
        return $this->getParticipantCount() >= $this->max_players;
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_REGISTRATION => 'Inscriptions ouvertes',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_FINISHED => 'Termine',
            self::STATUS_CANCELLED => 'Annule',
            default => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_REGISTRATION => 'green',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_FINISHED => 'blue',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }
}

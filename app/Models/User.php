<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Battle;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'coins',
        'wins',
        'losses',
        'current_rank',
        'last_daily_bonus_at',
        'has_selected_starter',
        'has_completed_first_draw',
        'starter_bronze_id',
        'current_session_id',
        'last_session_ip',
    ];

    /**
     * Configuration des rangs et leurs seuils de victoires
     */
    public const RANKS = [
        'bronze' => ['wins' => 0, 'name' => 'Chevalier de Bronze', 'icon' => '🥉', 'reward' => 0],
        'argent' => ['wins' => 20, 'name' => 'Chevalier d\'Argent', 'icon' => '🥈', 'reward' => 500],
        'or' => ['wins' => 50, 'name' => 'Chevalier d\'Or', 'icon' => '🥇', 'reward' => 1000],
        'divin' => ['wins' => 100, 'name' => 'Chevalier Divin', 'icon' => '👑', 'reward' => 2000],
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'has_selected_starter' => 'boolean',
        'has_completed_first_draw' => 'boolean',
        'last_daily_bonus_at' => 'datetime',
    ];

    /**
     * Relation : Un user possède plusieurs decks
     */
    public function decks(): HasMany
    {
        return $this->hasMany(Deck::class);
    }

    /**
     * Relation : Collection de cartes du joueur
     */
    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'user_cards')
            ->withPivot('quantity', 'obtained_at', 'fusion_level')
            ->withTimestamps();
    }

    /**
     * NOUVEAU : Relation avec le Bronze de départ sélectionné
     */
    public function starterBronze()
    {
        return $this->belongsTo(Card::class, 'starter_bronze_id');
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un joueur
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Ajoute des pièces au joueur
     */
    public function addCoins(int $amount): void
    {
        $this->increment('coins', $amount);
    }

    /**
     * Retire des pièces au joueur
     */
    public function spendCoins(int $amount): bool
    {
        if ($this->coins < $amount) {
            return false;
        }
        $this->decrement('coins', $amount);
        return true;
    }

    /**
     * Ajoute une carte à la collection
     */
    public function addCard(Card $card, int $quantity = 1): void
    {
        $existing = $this->cards()->where('card_id', $card->id)->first();

        if ($existing) {
            $this->cards()->updateExistingPivot($card->id, [
                'quantity' => $existing->pivot->quantity + $quantity,
            ]);
        } else {
            $this->cards()->attach($card->id, [
                'quantity' => $quantity,
                'obtained_at' => now(),
            ]);
        }
    }

    /**
     * Vérifie si le joueur possède une carte
     */
    public function hasCard(Card $card): bool
    {
        return $this->cards()->where('card_id', $card->id)->exists();
    }

    /**
     * NOUVEAU : Obtenir la quantité d'une carte spécifique
     */
    public function getCardQuantity(int $cardId): int
    {
        $card = $this->cards()->where('card_id', $cardId)->first();
        return $card ? $card->pivot->quantity : 0;
    }

    /**
     * Retire une carte de la collection
     *
     * @param int $cardId
     * @param int $quantity Quantité à retirer
     * @return bool True si réussi, false sinon
     */
    public function removeCard(int $cardId, int $quantity = 1): bool
    {
        $userCard = $this->cards()->where('card_id', $cardId)->first();

        if (!$userCard) {
            return false;
        }

        $currentQuantity = $userCard->pivot->quantity;

        if ($currentQuantity < $quantity) {
            return false;
        }

        $newQuantity = $currentQuantity - $quantity;

        if ($newQuantity <= 0) {
            $this->cards()->detach($cardId);
        } else {
            $this->cards()->updateExistingPivot($cardId, [
                'quantity' => $newQuantity,
            ]);
        }

        return true;
    }

    /**
     * Nombre total de cartes dans la collection
     */
    public function getTotalCardsAttribute(): int
    {
        return $this->cards()->sum('quantity');
    }

    /**
     * Enregistre une victoire
     * Retourne les informations de récompense de rang si un nouveau rang est atteint
     */
    public function recordWin(int $coinsReward = 100): ?array
    {
        $this->increment('wins');
        $this->refresh(); // Rafraîchir pour avoir le nouveau nombre de wins
        $this->addCoins($coinsReward);

        // Vérifier si un nouveau rang est atteint
        return $this->checkAndUpdateRank();
    }

    /**
     * Enregistre une défaite
     */
    public function recordLoss(int $coinsReward = 25): void
    {
        $this->increment('losses');
        $this->addCoins($coinsReward);
    }

    /**
     * Calcule le rang basé sur le nombre de victoires
     */
    public function calculateRank(): string
    {
        $wins = $this->wins;

        if ($wins >= self::RANKS['divin']['wins']) {
            return 'divin';
        } elseif ($wins >= self::RANKS['or']['wins']) {
            return 'or';
        } elseif ($wins >= self::RANKS['argent']['wins']) {
            return 'argent';
        }

        return 'bronze';
    }

    /**
     * Obtient les informations du rang actuel
     */
    public function getRankInfo(): array
    {
        $rank = $this->current_rank ?? 'bronze';
        return self::RANKS[$rank] ?? self::RANKS['bronze'];
    }

    /**
     * Obtient les informations du prochain rang
     */
    public function getNextRankInfo(): ?array
    {
        $rankOrder = ['bronze', 'argent', 'or', 'divin'];
        $currentIndex = array_search($this->current_rank ?? 'bronze', $rankOrder);

        if ($currentIndex < count($rankOrder) - 1) {
            $nextRank = $rankOrder[$currentIndex + 1];
            return array_merge(self::RANKS[$nextRank], ['key' => $nextRank]);
        }

        return null; // Déjà au rang max
    }

    /**
     * Vérifie et met à jour le rang si nécessaire
     * Retourne les informations de récompense si un nouveau rang est atteint
     */
    public function checkAndUpdateRank(): ?array
    {
        $newRank = $this->calculateRank();
        $currentRank = $this->current_rank ?? 'bronze';

        // Si le rang a changé (progression uniquement)
        $rankOrder = ['bronze', 'argent', 'or', 'divin'];
        $currentIndex = array_search($currentRank, $rankOrder);
        $newIndex = array_search($newRank, $rankOrder);

        if ($newIndex > $currentIndex) {
            // Nouveau rang atteint !
            $rankInfo = self::RANKS[$newRank];
            $reward = $rankInfo['reward'];

            // Mettre à jour le rang
            $this->current_rank = $newRank;
            $this->save();

            // Donner la récompense
            if ($reward > 0) {
                $this->addCoins($reward);
            }

            return [
                'new_rank' => $newRank,
                'rank_name' => $rankInfo['name'],
                'rank_icon' => $rankInfo['icon'],
                'reward' => $reward,
            ];
        }

        return null;
    }

    /**
     * Batailles en tant que joueur 1
     */
    public function battlesAsPlayer1()
    {
        return $this->hasMany(Battle::class, 'player1_id');
    }

    /**
     * Batailles en tant que joueur 2
     */
    public function battlesAsPlayer2()
    {
        return $this->hasMany(Battle::class, 'player2_id');
    }

    /**
     * Récupère la bataille active du joueur
     */
    public function activeBattle()
    {
        return Battle::where(function ($query) {
            $query->where('player1_id', $this->id)
                ->orWhere('player2_id', $this->id);
        })->whereIn('status', ['waiting', 'in_progress'])->first();
    }

    /**
     * Vérifie si le joueur est dans une bataille
     */
    public function isInBattle(): bool
    {
        return $this->activeBattle() !== null;
    }

    /**
     * Vérifie si le joueur peut récupérer son bonus quotidien
     */
    public function canClaimDailyBonus(): bool
    {
        if ($this->last_daily_bonus_at === null) {
            return true;
        }

        return $this->last_daily_bonus_at->diffInHours(now()) >= 24;
    }

    /**
     * Réclame le bonus quotidien (lance le dé)
     *
     * @return array ['success' => bool, 'dice_result' => int, 'coins_earned' => int]
     */
    public function claimDailyBonus(): array
    {
        if (!$this->canClaimDailyBonus()) {
            $hoursLeft = 24 - $this->last_daily_bonus_at->diffInHours(now());
            return [
                'success' => false,
                'message' => "Vous devez attendre encore {$hoursLeft}h pour votre prochain bonus.",
                'dice_result' => 0,
                'coins_earned' => 0,
            ];
        }

        // Lance le dé (1-6)
        $diceResult = rand(1, 6);
        $coinsEarned = $diceResult * 100;

        // Met à jour le joueur
        $this->last_daily_bonus_at = now();
        $this->save();
        $this->addCoins($coinsEarned);

        return [
            'success' => true,
            'message' => "Vous avez obtenu {$coinsEarned} pièces !",
            'dice_result' => $diceResult,
            'coins_earned' => $coinsEarned,
        ];
    }

    /**
     * Obtient le temps restant avant le prochain bonus
     *
     * @return array ['hours' => int, 'minutes' => int]
     */
    public function getTimeUntilNextBonus(): array
    {
        if ($this->canClaimDailyBonus()) {
            return ['hours' => 0, 'minutes' => 0];
        }

        $nextBonusTime = $this->last_daily_bonus_at->addHours(24);
        $diffInMinutes = now()->diffInMinutes($nextBonusTime, false);

        if ($diffInMinutes <= 0) {
            return ['hours' => 0, 'minutes' => 0];
        }

        return [
            'hours' => floor($diffInMinutes / 60),
            'minutes' => $diffInMinutes % 60,
        ];
    }
}
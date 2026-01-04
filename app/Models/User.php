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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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
            ->withPivot('quantity', 'obtained_at')
            ->withTimestamps();
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
     * Nombre total de cartes dans la collection
     */
    public function getTotalCardsAttribute(): int
    {
        return $this->cards()->sum('quantity');
    }

    /**
     * Enregistre une victoire
     */
    public function recordWin(int $coinsReward = 100): void
    {
        $this->increment('wins');
        $this->addCoins($coinsReward);
    }

    /**
     * Enregistre une défaite
     */
    public function recordLoss(int $coinsReward = 25): void
    {
        $this->increment('losses');
        $this->addCoins($coinsReward);
    }


    // Dans la classe User, ajouter :

    public function battlesAsPlayer1()
    {
        return $this->hasMany(Battle::class, 'player1_id');
    }

    public function battlesAsPlayer2()
    {
        return $this->hasMany(Battle::class, 'player2_id');
    }

    public function activeBattle()
    {
        return Battle::where(function ($query) {
            $query->where('player1_id', $this->id)
                ->orWhere('player2_id', $this->id);
        })->whereIn('status', ['waiting', 'in_progress'])->first();
    }

    public function isInBattle(): bool
    {
        return $this->activeBattle() !== null;
    }
}
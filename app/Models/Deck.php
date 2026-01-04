<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation : Un deck appartient à un user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un deck contient plusieurs cards
     */
    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class)->withPivot('quantity')->withTimestamps();
    }

    /**
     * Calcule le nombre total de cartes dans le deck
     */
    public function getTotalCardsAttribute(): int
    {
        return $this->cards->sum('pivot.quantity');
    }
}
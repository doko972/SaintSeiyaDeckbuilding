<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faction_id',
        'grade',
        'power_type',
        'element',
        'rarity',
        'health_points',
        'endurance',
        'defense',
        'power',
        'cosmos',
        'cost',
        'passive_ability_name',
        'passive_ability_description',
        'passive_effect_type',
        'passive_effect_value',
        'main_attack_id',
        'secondary_attack_1_id',
        'secondary_attack_2_id',
        'image_primary',
        'image_secondary',
    ];

    /**
     * Relation : Une card appartient à une faction
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class);
    }

    /**
     * Relation : Attaque principale
     */
    public function mainAttack(): BelongsTo
    {
        return $this->belongsTo(Attack::class, 'main_attack_id');
    }

    /**
     * Relation : Attaque secondaire 1
     */
    public function secondaryAttack1(): BelongsTo
    {
        return $this->belongsTo(Attack::class, 'secondary_attack_1_id');
    }

    /**
     * Relation : Attaque secondaire 2
     */
    public function secondaryAttack2(): BelongsTo
    {
        return $this->belongsTo(Attack::class, 'secondary_attack_2_id');
    }

    /**
     * Relation : Une card peut appartenir à plusieurs decks
     */
    public function decks(): BelongsToMany
    {
        return $this->belongsToMany(Deck::class)->withPivot('quantity')->withTimestamps();
    }

    /**
     * Relation : Images par niveau de fusion
     */
    public function cardImages(): HasMany
    {
        return $this->hasMany(CardImage::class)->orderBy('fusion_level');
    }

    /**
     * Retourne l'image pour un niveau de fusion donné.
     * Fallback vers le niveau 1 si le niveau demandé n'existe pas.
     */
    public function imageForLevel(int $level): ?CardImage
    {
        if ($this->relationLoaded('cardImages')) {
            return $this->cardImages->firstWhere('fusion_level', $level)
                ?? $this->cardImages->firstWhere('fusion_level', 1);
        }
        return $this->cardImages()->where('fusion_level', $level)->first()
            ?? $this->cardImages()->where('fusion_level', 1)->first();
    }
}
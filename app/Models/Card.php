<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faction_id',
        'grade',
        'armor_type',
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
}
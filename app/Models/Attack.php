<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'damage',
        'endurance_cost',
        'cosmos_cost',
        'effect_type',
        'effect_value',
    ];

    /**
     * Cards utilisant cette attaque comme attaque principale
     */
    public function cardsAsMainAttack(): HasMany
    {
        return $this->hasMany(Card::class, 'main_attack_id');
    }

    /**
     * Cards utilisant cette attaque comme attaque secondaire 1
     */
    public function cardsAsSecondaryAttack1(): HasMany
    {
        return $this->hasMany(Card::class, 'secondary_attack_1_id');
    }

    /**
     * Cards utilisant cette attaque comme attaque secondaire 2
     */
    public function cardsAsSecondaryAttack2(): HasMany
    {
        return $this->hasMany(Card::class, 'secondary_attack_2_id');
    }
}
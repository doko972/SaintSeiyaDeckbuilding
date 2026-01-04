<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color_primary',
        'color_secondary',
        'image',
    ];

    /**
     * Relation : Une faction possède plusieurs cards
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
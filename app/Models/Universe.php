<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Universe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color_primary',
        'color_secondary',
        'logo_image',
    ];

    public function factions(): HasMany
    {
        return $this->hasMany(Faction::class);
    }
}

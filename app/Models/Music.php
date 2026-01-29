<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = 'musics';

    protected $fillable = [
        'name',
        'file_path',
        'type',
        'is_active',
        'volume',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'volume' => 'integer',
    ];

    /**
     * Scope pour les musiques actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope par type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Récupérer une musique aléatoire de combat
     */
    public static function getRandomBattleMusic()
    {
        return self::active()->ofType('battle')->inRandomOrder()->first();
    }

    /**
     * Récupérer toutes les musiques de combat actives
     */
    public static function getBattleMusics()
    {
        return self::active()->ofType('battle')->get();
    }

    /**
     * Récupérer une musique de victoire aléatoire
     */
    public static function getRandomVictoryMusic()
    {
        return self::active()->ofType('victory')->inRandomOrder()->first();
    }

    /**
     * Récupérer une musique de défaite aléatoire
     */
    public static function getRandomDefeatMusic()
    {
        return self::active()->ofType('defeat')->inRandomOrder()->first();
    }
}

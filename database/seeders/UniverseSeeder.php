<?php

namespace Database\Seeders;

use App\Models\Faction;
use App\Models\Universe;
use Illuminate\Database\Seeder;

class UniverseSeeder extends Seeder
{
    public function run(): void
    {
        $universes = [
            [
                'name'            => 'Saint Seiya',
                'slug'            => 'saint-seiya',
                'description'     => 'Les Chevaliers du Zodiaque protègent la déesse Athéna contre des forces obscures.',
                'color_primary'   => '#FFD700',
                'color_secondary' => '#CD7F32',
                'logo_image'      => null,
            ],
            [
                'name'            => 'Dragon Ball',
                'slug'            => 'dragon-ball',
                'description'     => 'Les guerriers Z défendent la Terre contre des menaces cosmiques toujours plus puissantes.',
                'color_primary'   => '#F97316',
                'color_secondary' => '#DC2626',
                'logo_image'      => null,
            ],
            [
                'name'            => 'Naruto',
                'slug'            => 'naruto',
                'description'     => 'Les ninja des villages affrontent l\'obscurité pour protéger leurs proches et leur monde.',
                'color_primary'   => '#EF4444',
                'color_secondary' => '#F97316',
                'logo_image'      => null,
            ],
            [
                'name'            => 'One Piece',
                'slug'            => 'one-piece',
                'description'     => 'Les pirates des Grands Fonds cherchent le légendaire trésor One Piece.',
                'color_primary'   => '#3B82F6',
                'color_secondary' => '#EF4444',
                'logo_image'      => null,
            ],
            [
                'name'            => 'Bleach',
                'slug'            => 'bleach',
                'description'     => 'Les shinigamis et arrancar s\'affrontent entre les mondes des vivants et des morts.',
                'color_primary'   => '#6366F1',
                'color_secondary' => '#8B5CF6',
                'logo_image'      => null,
            ],
        ];

        foreach ($universes as $data) {
            Universe::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // Assigner les factions Saint Seiya existantes à l'univers Saint Seiya
        $saintSeiya = Universe::where('slug', 'saint-seiya')->first();
        if ($saintSeiya) {
            Faction::whereIn('name', [
                'Chevaliers de Bronze',
                'Chevaliers d\'Argent',
                'Chevaliers d\'Or',
                'Spectres d\'Hadès',
                'Marinas de Poséidon',
                'Guerriers Divins d\'Asgard',
            ])->update(['universe_id' => $saintSeiya->id]);
        }
    }
}

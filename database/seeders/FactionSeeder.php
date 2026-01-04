<?php

namespace Database\Seeders;

use App\Models\Faction;
use Illuminate\Database\Seeder;

class FactionSeeder extends Seeder
{
    public function run(): void
    {
        $factions = [
            [
                'name' => 'Chevaliers de Bronze',
                'description' => 'Les Chevaliers de Bronze sont les protecteurs d\'Athéna. Bien que considérés comme les plus faibles, leur détermination et leur courage les rendent redoutables.',
                'color_primary' => '#CD7F32',
                'color_secondary' => '#8B4513',
            ],
            [
                'name' => 'Chevaliers d\'Argent',
                'description' => 'Les Chevaliers d\'Argent sont plus puissants que les Chevaliers de Bronze. Ils servent souvent de messagers et d\'exécuteurs pour le Sanctuaire.',
                'color_primary' => '#C0C0C0',
                'color_secondary' => '#808080',
            ],
            [
                'name' => 'Chevaliers d\'Or',
                'description' => 'Les Chevaliers d\'Or sont l\'élite du Sanctuaire. Chacun protège une maison du zodiaque et possède une puissance cosmique extraordinaire.',
                'color_primary' => '#FFD700',
                'color_secondary' => '#DAA520',
            ],
            [
                'name' => 'Spectres d\'Hadès',
                'description' => 'Les Spectres sont les guerriers du dieu des Enfers, Hadès. Ils portent des Surplis noirs et cherchent à plonger le monde dans les ténèbres.',
                'color_primary' => '#4B0082',
                'color_secondary' => '#1a0033',
            ],
            [
                'name' => 'Marinas de Poséidon',
                'description' => 'Les Marinas sont les guerriers du dieu des mers, Poséidon. Ils portent des Écailles et contrôlent les forces océaniques.',
                'color_primary' => '#006994',
                'color_secondary' => '#004466',
            ],
            [
                'name' => 'Guerriers Divins d\'Asgard',
                'description' => 'Les Guerriers Divins protègent le royaume d\'Asgard sous les ordres d\'Hilda. Ils portent des armures représentant les étoiles d\'Odin.',
                'color_primary' => '#87CEEB',
                'color_secondary' => '#4682B4',
            ],
        ];

        foreach ($factions as $faction) {
            Faction::create($faction);
        }
    }
}
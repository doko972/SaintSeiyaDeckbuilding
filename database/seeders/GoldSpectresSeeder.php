<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class GoldSpectresSeeder extends Seeder
{
    /**
     * Seeder pour les Chevaliers d'Or ressuscités en Spectres
     * Shion, Saga, Deathmask, Shura, Camus, Aphrodite
     * Saga d'Hadès - Les traîtres du Sanctuaire
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Spectres Rebelles
        $faction = Faction::firstOrCreate(
            ['name' => 'Spectres Rebelles'],
            [
                'description' => 'Les Chevaliers d\'Or ressuscités par Hadès, corrompus par les ténèbres. Portant désormais des Surplis noirs, ils servent le dieu des Enfers tout en gardant leur puissance légendaire.',
                'color_primary' => '#6B21A8',   // Violet profond
                'color_secondary' => '#1E1B4B', // Indigo sombre
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === SHION DU BÉLIER SPECTRE ===
            'shion_spectre_1' => Attack::create([
                'name' => 'Dark Crystal Wall',
                'description' => 'Le mur de cristal corrompu par les ténèbres.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'buff_defense',
                'effect_value' => 25,
            ]),
            'shion_spectre_2' => Attack::create([
                'name' => 'Stardust Revolution Darkness',
                'description' => 'La révolution de poussière d\'étoiles imprégnée des ténèbres.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'shion_spectre_3' => Attack::create([
                'name' => 'Extinction Starlight',
                'description' => 'La lumière des étoiles qui s\'éteint, annihilant tout.',
                'damage' => 200,
                'endurance_cost' => 75,
                'cosmos_cost' => 10,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ]),

            // === SAGA DES GÉMEAUX SPECTRE ===
            'saga_spectre_1' => Attack::create([
                'name' => 'Dark Another Dimension',
                'description' => 'La dimension alternative corrompue par le mal.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'saga_spectre_2' => Attack::create([
                'name' => 'Galaxian Explosion Abyss',
                'description' => 'L\'explosion galactique des abysses.',
                'damage' => 170,
                'endurance_cost' => 60,
                'cosmos_cost' => 9,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'saga_spectre_3' => Attack::create([
                'name' => 'Demon Emperor Fist',
                'description' => 'Le poing de l\'empereur démon dans toute sa puissance maléfique.',
                'damage' => 210,
                'endurance_cost' => 80,
                'cosmos_cost' => 10,
                'effect_type' => 'debuff',
                'effect_value' => 35,
            ]),

            // === DEATHMASK DU CANCER SPECTRE ===
            'deathmask_spectre_1' => Attack::create([
                'name' => 'Hades Waves',
                'description' => 'Les vagues d\'Hadès emportent les âmes.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'deathmask_spectre_2' => Attack::create([
                'name' => 'Praesepe Underworld',
                'description' => 'Les âmes de Praesepe invoquées depuis les Enfers.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'drain',
                'effect_value' => 25,
            ]),
            'deathmask_spectre_3' => Attack::create([
                'name' => 'Sekishiki Meikai Ha Abyss',
                'description' => 'Les vagues des morts des Enfers absolus.',
                'damage' => 180,
                'endurance_cost' => 70,
                'cosmos_cost' => 9,
                'effect_type' => 'drain',
                'effect_value' => 35,
            ]),

            // === SHURA DU CAPRICORNE SPECTRE ===
            'shura_spectre_1' => Attack::create([
                'name' => 'Dark Excalibur',
                'description' => 'L\'épée sacrée corrompue par les ténèbres.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'shura_spectre_2' => Attack::create([
                'name' => 'Jumping Stone Abyss',
                'description' => 'La pierre bondissante des abysses.',
                'damage' => 145,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'shura_spectre_3' => Attack::create([
                'name' => 'Excalibur of Darkness',
                'description' => 'L\'Excalibur ultime imprégnée des ténèbres d\'Hadès.',
                'damage' => 195,
                'endurance_cost' => 75,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === CAMUS DU VERSEAU SPECTRE ===
            'camus_spectre_1' => Attack::create([
                'name' => 'Dark Diamond Dust',
                'description' => 'La poussière de diamant corrompue par les ténèbres.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),
            'camus_spectre_2' => Attack::create([
                'name' => 'Freezing Coffin Abyss',
                'description' => 'Le cercueil de glace des abysses.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),
            'camus_spectre_3' => Attack::create([
                'name' => 'Aurora Execution Darkness',
                'description' => 'L\'exécution de l\'aurore des ténèbres, le zéro absolu corrompu.',
                'damage' => 190,
                'endurance_cost' => 75,
                'cosmos_cost' => 10,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),

            // === APHRODITE DES POISSONS SPECTRE ===
            'aphrodite_spectre_1' => Attack::create([
                'name' => 'Dark Royal Demon Rose',
                'description' => 'Les roses démoniaques royales des ténèbres.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'aphrodite_spectre_2' => Attack::create([
                'name' => 'Piranha Rose Abyss',
                'description' => 'Les roses piranha des abysses dévorent tout.',
                'damage' => 130,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),
            'aphrodite_spectre_3' => Attack::create([
                'name' => 'Bloody Rose of Hades',
                'description' => 'La rose sanglante d\'Hadès, mortelle et magnifique.',
                'damage' => 175,
                'endurance_cost' => 65,
                'cosmos_cost' => 9,
                'effect_type' => 'burn',
                'effect_value' => 30,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================

        $cards = [
            // === SHION DU BÉLIER SPECTRE === (Coût 9 - Technique)
            [
                'name' => 'Shion du Bélier Spectre',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 220,
                'endurance' => 100,
                'defense' => 105,
                'power' => 72,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['shion_spectre_1']->id,
                'secondary_attack_1_id' => $attacks['shion_spectre_2']->id,
                'secondary_attack_2_id' => $attacks['shion_spectre_3']->id,
            ],

            // === SAGA DES GÉMEAUX SPECTRE === (Coût 10 - Attaquant)
            [
                'name' => 'Saga des Gémeaux Spectre',
                'faction_id' => $factionId,
                'cost' => 10,
                'health_points' => 230,
                'endurance' => 120,
                'defense' => 90,
                'power' => 95,
                'cosmos' => 10,
                'rarity' => 'legendary',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['saga_spectre_1']->id,
                'secondary_attack_1_id' => $attacks['saga_spectre_2']->id,
                'secondary_attack_2_id' => $attacks['saga_spectre_3']->id,
            ],

            // === DEATHMASK DU CANCER SPECTRE === (Coût 8 - Technique)
            [
                'name' => 'Deathmask du Cancer Spectre',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 195,
                'endurance' => 90,
                'defense' => 85,
                'power' => 68,
                'cosmos' => 8,
                'rarity' => 'epic',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['deathmask_spectre_1']->id,
                'secondary_attack_1_id' => $attacks['deathmask_spectre_2']->id,
                'secondary_attack_2_id' => $attacks['deathmask_spectre_3']->id,
            ],

            // === SHURA DU CAPRICORNE SPECTRE === (Coût 9 - Attaquant)
            [
                'name' => 'Shura du Capricorne Spectre',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 210,
                'endurance' => 115,
                'defense' => 75,
                'power' => 90,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['shura_spectre_1']->id,
                'secondary_attack_1_id' => $attacks['shura_spectre_2']->id,
                'secondary_attack_2_id' => $attacks['shura_spectre_3']->id,
            ],

            // === CAMUS DU VERSEAU SPECTRE === (Coût 9 - Technique)
            [
                'name' => 'Camus du Verseau Spectre',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 205,
                'endurance' => 95,
                'defense' => 95,
                'power' => 78,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'armor_type' => 'surplis',
                'element' => 'ice',
                'main_attack_id' => $attacks['camus_spectre_1']->id,
                'secondary_attack_1_id' => $attacks['camus_spectre_2']->id,
                'secondary_attack_2_id' => $attacks['camus_spectre_3']->id,
            ],

            // === APHRODITE DES POISSONS SPECTRE === (Coût 8 - Technique)
            [
                'name' => 'Aphrodite des Poissons Spectre',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 185,
                'endurance' => 85,
                'defense' => 80,
                'power' => 72,
                'cosmos' => 8,
                'rarity' => 'epic',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['aphrodite_spectre_1']->id,
                'secondary_attack_1_id' => $attacks['aphrodite_spectre_2']->id,
                'secondary_attack_2_id' => $attacks['aphrodite_spectre_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 6 Chevaliers d\'Or Spectres créés avec succès !');
        $this->command->info('✅ 18 Attaques créées avec succès !');
        $this->command->info('⚰️ Les traîtres du Sanctuaire rejoignent Hadès !');
    }
}

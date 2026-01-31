<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Faction;
use App\Models\Attack;
use Illuminate\Database\Seeder;

class CommonCardSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les factions
        $bronze = Faction::where('name', 'Chevaliers de Bronze')->first();
        $spectres = Faction::where('name', 'Spectres d\'Hadès')->first();

        if (!$bronze || !$spectres) {
            $this->command->error('Les factions Bronze et Spectres doivent exister.');
            return;
        }

        // Créer une attaque basique si elle n'existe pas
        $basicAttack = Attack::firstOrCreate(
            ['name' => 'Coup de poing'],
            [
                'description' => 'Un coup de poing basique',
                'damage' => 15,
                'endurance_cost' => 5,
                'cosmos_cost' => 0,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]
        );

        $defendAttack = Attack::firstOrCreate(
            ['name' => 'Garde'],
            [
                'description' => 'Position défensive',
                'damage' => 0,
                'endurance_cost' => 0,
                'cosmos_cost' => 0,
                'effect_type' => 'buff_defense',
                'effect_value' => 5,
            ]
        );

        $cards = [
            // Soldats du Sanctuaire
            [
                'name' => 'Soldat du Sanctuaire',
                'faction_id' => $bronze->id,
                'grade' => 1,
                'armor_type' => 'bronze',
                'element' => 'light',
                'rarity' => 'common',
                'health_points' => 50,
                'endurance' => 30,
                'defense' => 5,
                'power' => 15,
                'cosmos' => 10,
                'cost' => 1,
                'passive_ability_name' => null,
                'passive_ability_description' => null,
                'main_attack_id' => $basicAttack->id,
                'secondary_attack_1_id' => $defendAttack->id,
                'secondary_attack_2_id' => null,
            ],
            // Garde d'Argent
            [
                'name' => 'Garde d\'Argent',
                'faction_id' => $bronze->id,
                'grade' => 2,
                'armor_type' => 'silver',
                'element' => 'light',
                'rarity' => 'common',
                'health_points' => 60,
                'endurance' => 35,
                'defense' => 8,
                'power' => 18,
                'cosmos' => 15,
                'cost' => 1,
                'passive_ability_name' => null,
                'passive_ability_description' => null,
                'main_attack_id' => $basicAttack->id,
                'secondary_attack_1_id' => $defendAttack->id,
                'secondary_attack_2_id' => null,
            ],
            // Apprenti Chevalier
            [
                'name' => 'Apprenti Chevalier',
                'faction_id' => $bronze->id,
                'grade' => 2,
                'armor_type' => 'bronze',
                'element' => 'light',
                'rarity' => 'common',
                'health_points' => 55,
                'endurance' => 40,
                'defense' => 6,
                'power' => 20,
                'cosmos' => 20,
                'cost' => 1,
                'passive_ability_name' => 'Determination',
                'passive_ability_description' => '+5% de puissance quand PV < 50%',
                'main_attack_id' => $basicAttack->id,
                'secondary_attack_1_id' => $defendAttack->id,
                'secondary_attack_2_id' => null,
            ],
            // Sentinelle du Sanctuaire
            [
                'name' => 'Sentinelle du Sanctuaire',
                'faction_id' => $bronze->id,
                'grade' => 2,
                'armor_type' => 'bronze',
                'element' => 'earth',
                'rarity' => 'common',
                'health_points' => 70,
                'endurance' => 35,
                'defense' => 10,
                'power' => 12,
                'cosmos' => 12,
                'cost' => 1,
                'passive_ability_name' => 'Vigilance',
                'passive_ability_description' => '+3 defense',
                'main_attack_id' => $basicAttack->id,
                'secondary_attack_1_id' => $defendAttack->id,
                'secondary_attack_2_id' => null,
            ],
            // Soldat Spectre
            [
                'name' => 'Soldat Spectre',
                'faction_id' => $spectres->id,
                'grade' => 1,
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'rarity' => 'common',
                'health_points' => 45,
                'endurance' => 25,
                'defense' => 4,
                'power' => 18,
                'cosmos' => 15,
                'cost' => 1,
                'passive_ability_name' => null,
                'passive_ability_description' => null,
                'main_attack_id' => $basicAttack->id,
                'secondary_attack_1_id' => $defendAttack->id,
                'secondary_attack_2_id' => null,
            ],
            // Squelette des Enfers
            [
                'name' => 'Squelette des Enfers',
                'faction_id' => $spectres->id,
                'grade' => 1,
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'rarity' => 'common',
                'health_points' => 40,
                'endurance' => 20,
                'defense' => 3,
                'power' => 20,
                'cosmos' => 10,
                'cost' => 1,
                'passive_ability_name' => 'Immortel',
                'passive_ability_description' => '10% de chance de survivre avec 1 PV',
                'main_attack_id' => $basicAttack->id,
                'secondary_attack_1_id' => $defendAttack->id,
                'secondary_attack_2_id' => null,
            ],
        ];

        foreach ($cards as $card) {
            Card::firstOrCreate(
                ['name' => $card['name']],
                $card
            );
        }

        $this->command->info('Cartes common creees avec succes !');
    }
}

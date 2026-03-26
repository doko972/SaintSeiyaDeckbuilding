<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class BronzeSaintsSecondarySeeder extends Seeder
{
    /**
     * Seeder pour les Chevaliers de Bronze secondaires
     * Jabu, Ichi, Geki, Nachi, Ban, June
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Chevaliers de Bronze
        $faction = Faction::firstOrCreate(
            ['name' => 'Chevaliers de Bronze'],
            [
                'description' => 'Les Chevaliers de Bronze sont les protecteurs d\'Athéna au rang le plus bas mais au courage le plus grand. Ils portent les armures de Bronze et se battent pour la justice.',
                'color_primary' => '#CD7F32',   // Bronze
                'color_secondary' => '#8B4513', // Brun
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === JABU DE LA LICORNE (Unicorn) ===
            'jabu_1' => Attack::create([
                'name' => 'Unicorn Gallop',
                'description' => 'Jabu charge l\'ennemi avec la vitesse de la licorne.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'jabu_2' => Attack::create([
                'name' => 'Unicorn Horn',
                'description' => 'Un coup de corne dévastateur qui transperce les défenses.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'jabu_3' => Attack::create([
                'name' => 'Unicorn Rolling Crash',
                'description' => 'Jabu effectue une rotation dévastatrice en plongeant sur l\'ennemi.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === ICHI DE L'HYDRE (Hydra) ===
            'ichi_1' => Attack::create([
                'name' => 'Hydra Fang',
                'description' => 'Les crocs venimeux de l\'Hydre mordent.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'ichi_2' => Attack::create([
                'name' => 'Hydra Venom Strike',
                'description' => 'Une attaque imprégnée du venin mortel de l\'Hydre.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'ichi_3' => Attack::create([
                'name' => 'Hydra Multi-Head Assault',
                'description' => 'Ichi frappe comme les multiples têtes de l\'Hydre.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),

            // === GEKI DE L'OURS (Bear) ===
            'geki_1' => Attack::create([
                'name' => 'Bear Claw',
                'description' => 'Les puissantes griffes de l\'ours déchirent.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'geki_2' => Attack::create([
                'name' => 'Bear Hug Crush',
                'description' => 'L\'étreinte de l\'ours broie les os de l\'adversaire.',
                'damage' => 115,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'geki_3' => Attack::create([
                'name' => 'Hanging Bear',
                'description' => 'La prise signature de Geki qui immobilise totalement l\'ennemi.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === NACHI DU LOUP (Wolf) ===
            'nachi_1' => Attack::create([
                'name' => 'Wolf Fang',
                'description' => 'Les crocs du loup mordent profondément.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'burn',
                'effect_value' => 8,
            ]),
            'nachi_2' => Attack::create([
                'name' => 'Dead Howling',
                'description' => 'Le hurlement mortel du loup paralyse de terreur.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'nachi_3' => Attack::create([
                'name' => 'Wolf Cruelty Claw',
                'description' => 'Les griffes cruelles du loup déchirent sans pitié.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BAN DU LION (Lion) ===
            'ban_1' => Attack::create([
                'name' => 'Lion Roar',
                'description' => 'Le rugissement du lion terrifie l\'adversaire.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 10,
            ]),
            'ban_2' => Attack::create([
                'name' => 'Lionet Bomber',
                'description' => 'Ban projette son cosmos en une explosion dévastatrice.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'ban_3' => Attack::create([
                'name' => 'Lion Glory',
                'description' => 'La gloire du lion brille de mille feux.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'buff_attack',
                'effect_value' => 15,
            ]),

            // === JUNE DU CAMÉLÉON (Chameleon) ===
            'june_1' => Attack::create([
                'name' => 'Chameleon Whip',
                'description' => 'Le fouet de June frappe avec précision.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'june_2' => Attack::create([
                'name' => 'Invisible Strike',
                'description' => 'June devient invisible comme le caméléon et frappe par surprise.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'june_3' => Attack::create([
                'name' => 'Chameleon Tongue Lash',
                'description' => 'Le fouet s\'étend comme la langue du caméléon pour atteindre l\'ennemi.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================

        $cards = [
            // === JABU DE LA LICORNE === (Coût 5 - Équilibré)
            [
                'name' => 'Jabu de la Licorne',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 140,
                'endurance' => 80,
                'defense' => 50,
                'power' => 48,
                'cosmos' => 5,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'light',
                'main_attack_id' => $attacks['jabu_1']->id,
                'secondary_attack_1_id' => $attacks['jabu_2']->id,
                'secondary_attack_2_id' => $attacks['jabu_3']->id,
            ],

            // === ICHI DE L'HYDRE === (Coût 4 - Technique)
            [
                'name' => 'Ichi de l\'Hydre',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 105,
                'endurance' => 65,
                'defense' => 45,
                'power' => 38,
                'cosmos' => 4,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'water',
                'main_attack_id' => $attacks['ichi_1']->id,
                'secondary_attack_1_id' => $attacks['ichi_2']->id,
                'secondary_attack_2_id' => $attacks['ichi_3']->id,
            ],

            // === GEKI DE L'OURS === (Coût 5 - Tank)
            [
                'name' => 'Geki de l\'Ours',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 165,
                'endurance' => 70,
                'defense' => 65,
                'power' => 32,
                'cosmos' => 5,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'earth',
                'main_attack_id' => $attacks['geki_1']->id,
                'secondary_attack_1_id' => $attacks['geki_2']->id,
                'secondary_attack_2_id' => $attacks['geki_3']->id,
            ],

            // === NACHI DU LOUP === (Coût 4 - Agile)
            [
                'name' => 'Nachi du Loup',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 100,
                'endurance' => 75,
                'defense' => 35,
                'power' => 45,
                'cosmos' => 4,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'earth',
                'main_attack_id' => $attacks['nachi_1']->id,
                'secondary_attack_1_id' => $attacks['nachi_2']->id,
                'secondary_attack_2_id' => $attacks['nachi_3']->id,
            ],

            // === BAN DU LION === (Coût 5 - Bruiser)
            [
                'name' => 'Ban du Lionet',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 145,
                'endurance' => 80,
                'defense' => 50,
                'power' => 50,
                'cosmos' => 5,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'fire',
                'main_attack_id' => $attacks['ban_1']->id,
                'secondary_attack_1_id' => $attacks['ban_2']->id,
                'secondary_attack_2_id' => $attacks['ban_3']->id,
            ],

            // === JUNE DU CAMÉLÉON === (Coût 4 - Technique)
            [
                'name' => 'June du Caméléon',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 95,
                'endurance' => 70,
                'defense' => 40,
                'power' => 40,
                'cosmos' => 4,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'earth',
                'main_attack_id' => $attacks['june_1']->id,
                'secondary_attack_1_id' => $attacks['june_2']->id,
                'secondary_attack_2_id' => $attacks['june_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 6 Chevaliers de Bronze secondaires créés avec succès !');
        $this->command->info('✅ 18 Attaques créées avec succès !');
        $this->command->info('🦄 Jabu, Ichi, Geki, Nachi, Ban et June rejoignent le combat !');
    }
}

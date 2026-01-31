<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class SteelSaintsSeeder extends Seeder
{
    /**
     * Seeder pour les Chevaliers d'Acier
     * Daichi, Shô, Ushô - Personnages exclusifs à l'anime
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Chevaliers d'Acier
        $faction = Faction::firstOrCreate(
            ['name' => 'Chevaliers d\'Acier'],
            [
                'description' => 'Les Chevaliers d\'Acier sont des guerriers équipés d\'armures mécaniques high-tech créées par la Fondation Graad. Bien que n\'utilisant pas le cosmos, leur technologie leur permet de rivaliser avec les Chevaliers de Bronze.',
                'color_primary' => '#71717A',   // Gris acier
                'color_secondary' => '#3F3F46', // Gris foncé
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === DAICHI DE L'OURS D'ACIER (Steel Land / Bear) ===
            'daichi_1' => Attack::create([
                'name' => 'Steel Bear Claw',
                'description' => 'Les griffes mécaniques de l\'ours d\'acier déchirent.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'daichi_2' => Attack::create([
                'name' => 'Land Crusher',
                'description' => 'Daichi utilise la puissance de son armure pour broyer le sol.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'daichi_3' => Attack::create([
                'name' => 'Steel Bear Hug',
                'description' => 'L\'étreinte de l\'ours d\'acier broie les os avec une force mécanique.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === SHÔ DU CIEL D'ACIER (Steel Sky) ===
            'sho_1' => Attack::create([
                'name' => 'Steel Hurricane',
                'description' => 'Shô crée un ouragan avec les propulseurs de son armure.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'sho_2' => Attack::create([
                'name' => 'Sky Dive Attack',
                'description' => 'Shô plonge du ciel à grande vitesse sur l\'ennemi.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'sho_3' => Attack::create([
                'name' => 'Sonic Boom Assault',
                'description' => 'Shô dépasse le mur du son, créant une onde de choc dévastatrice.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),

            // === USHÔ DE LA MER D'ACIER (Steel Marine) ===
            'usho_1' => Attack::create([
                'name' => 'Steel Torpedo',
                'description' => 'Ushô lance une torpille depuis son armure marine.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'usho_2' => Attack::create([
                'name' => 'Deep Pressure Strike',
                'description' => 'Une attaque utilisant la pression des profondeurs.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 12,
            ]),
            'usho_3' => Attack::create([
                'name' => 'Marine Blitz',
                'description' => 'Ushô déchaîne toute la puissance de son armure marine.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================

        $cards = [
            // === DAICHI DE L'OURS D'ACIER === (Coût 5 - Tank)
            [
                'name' => 'Daichi de l\'Ours d\'Acier',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 160,
                'endurance' => 70,
                'defense' => 65,
                'power' => 35,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'bronze',
                'element' => 'earth',
                'main_attack_id' => $attacks['daichi_1']->id,
                'secondary_attack_1_id' => $attacks['daichi_2']->id,
                'secondary_attack_2_id' => $attacks['daichi_3']->id,
            ],

            // === SHÔ DU CIEL D'ACIER === (Coût 4 - Agile)
            [
                'name' => 'Shô du Ciel d\'Acier',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 105,
                'endurance' => 80,
                'defense' => 35,
                'power' => 45,
                'cosmos' => 4,
                'rarity' => 'rare',
                'armor_type' => 'bronze',
                'element' => 'thunder',
                'main_attack_id' => $attacks['sho_1']->id,
                'secondary_attack_1_id' => $attacks['sho_2']->id,
                'secondary_attack_2_id' => $attacks['sho_3']->id,
            ],

            // === USHÔ DE LA MER D'ACIER === (Coût 4 - Équilibré)
            [
                'name' => 'Ushô de la Mer d\'Acier',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 110,
                'endurance' => 70,
                'defense' => 45,
                'power' => 40,
                'cosmos' => 4,
                'rarity' => 'rare',
                'armor_type' => 'bronze',
                'element' => 'water',
                'main_attack_id' => $attacks['usho_1']->id,
                'secondary_attack_1_id' => $attacks['usho_2']->id,
                'secondary_attack_2_id' => $attacks['usho_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 3 Chevaliers d\'Acier créés avec succès !');
        $this->command->info('✅ 9 Attaques créées avec succès !');
        $this->command->info('⚙️ Daichi, Shô et Ushô rejoignent le combat !');
    }
}

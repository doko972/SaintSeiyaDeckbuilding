<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class SpectresSecondarySeeder extends Seeder
{
    /**
     * Seeder pour les Spectres secondaires d'Hadès
     * Laimi, Ox, Miles, Iwan, Rock, Flégias, Stand, Markino, Gordon
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Spectres d'Hadès
        $faction = Faction::firstOrCreate(
            ['name' => 'Spectres d\'Hadès'],
            [
                'description' => 'Les Spectres sont les 108 guerriers démoniaques au service d\'Hadès, dieu des Enfers. Ils portent les Surplis, armures noires imprégnées des ténèbres.',
                'color_primary' => '#4B0082',   // Indigo
                'color_secondary' => '#1A1A2E', // Noir bleuté
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === LAIMI DU VER DE TERRE (Earthworm) ===
            'laimi_1' => Attack::create([
                'name' => 'Underground Ambush',
                'description' => 'Laimi surgit de sous terre pour attaquer.',
                'damage' => 60,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'laimi_2' => Attack::create([
                'name' => 'Earthworm Coil',
                'description' => 'Laimi s\'enroule autour de sa proie pour l\'étouffer.',
                'damage' => 90,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'laimi_3' => Attack::create([
                'name' => 'Subterranean Assault',
                'description' => 'Une attaque dévastatrice depuis les profondeurs de la terre.',
                'damage' => 120,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === OX DU GORGONE (Gorgon) ===
            'ox_1' => Attack::create([
                'name' => 'Petrifying Gaze',
                'description' => 'Le regard de la Gorgone commence à pétrifier.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 10,
            ]),
            'ox_2' => Attack::create([
                'name' => 'Serpent Hair Strike',
                'description' => 'Les serpents de la chevelure mordent l\'ennemi.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 12,
            ]),
            'ox_3' => Attack::create([
                'name' => 'Medusa\'s Curse',
                'description' => 'La malédiction de Méduse s\'abat sur l\'adversaire.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === MILES DE L'ELFE (Elf) ===
            'miles_1' => Attack::create([
                'name' => 'Elf Arrow',
                'description' => 'Une flèche elfique précise et rapide.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'miles_2' => Attack::create([
                'name' => 'Spirit Drain',
                'description' => 'Miles absorbe l\'énergie vitale de l\'ennemi.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'drain',
                'effect_value' => 20,
            ]),
            'miles_3' => Attack::create([
                'name' => 'Dark Elf Magic',
                'description' => 'La magie noire des elfes des ténèbres.',
                'damage' => 125,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),

            // === IWAN DU TROLL ===
            'iwan_1' => Attack::create([
                'name' => 'Troll Smash',
                'description' => 'Un coup de poing brutal de troll.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'iwan_2' => Attack::create([
                'name' => 'Regenerating Strike',
                'description' => 'Iwan frappe tout en régénérant ses blessures.',
                'damage' => 105,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'heal',
                'effect_value' => 20,
            ]),
            'iwan_3' => Attack::create([
                'name' => 'Troll Rampage',
                'description' => 'Le troll entre dans une rage destructrice.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === ROCK DU GOLEM ===
            'rock_1' => Attack::create([
                'name' => 'Stone Fist',
                'description' => 'Un poing de pierre solide comme le roc.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'rock_2' => Attack::create([
                'name' => 'Rock Shield',
                'description' => 'Rock se protège d\'une armure de pierre supplémentaire.',
                'damage' => 90,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'buff_defense',
                'effect_value' => 25,
            ]),
            'rock_3' => Attack::create([
                'name' => 'Golem Crusher',
                'description' => 'L\'attaque dévastatrice du Golem écrase tout.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === FLÉGIAS DU LYCAON (passeur du Styx) ===
            'flegias_1' => Attack::create([
                'name' => 'Oar Strike',
                'description' => 'Flégias frappe avec sa rame du Styx.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'flegias_2' => Attack::create([
                'name' => 'Styx Torrent',
                'description' => 'Les eaux maudites du Styx submergent l\'ennemi.',
                'damage' => 105,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'flegias_3' => Attack::create([
                'name' => 'Howling Paddle',
                'description' => 'Le hurlement du Lycaon accompagne un coup de rame dévastateur.',
                'damage' => 135,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === STAND DU SCARABÉE MORTEL (Deadly Beetle) ===
            'stand_1' => Attack::create([
                'name' => 'Beetle Charge',
                'description' => 'Stand charge avec la carapace du scarabée.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'stand_2' => Attack::create([
                'name' => 'Deadly Pincers',
                'description' => 'Les pinces mortelles du scarabée broient.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'stand_3' => Attack::create([
                'name' => 'Death Beetle Swarm',
                'description' => 'Une nuée de scarabées mortels dévore l\'ennemi.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),

            // === MARKINO DU SQUELETTE (Skeleton) ===
            'markino_1' => Attack::create([
                'name' => 'Bone Strike',
                'description' => 'Markino frappe avec ses os tranchants.',
                'damage' => 60,
                'endurance_cost' => 15,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'markino_2' => Attack::create([
                'name' => 'Skeleton Army',
                'description' => 'Markino invoque d\'autres squelettes à l\'attaque.',
                'damage' => 90,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'markino_3' => Attack::create([
                'name' => 'Death\'s Embrace',
                'description' => 'L\'étreinte de la mort glace le sang.',
                'damage' => 115,
                'endurance_cost' => 45,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),

            // === GORDON DU MINOTAURE (Minotauros) ===
            'gordon_1' => Attack::create([
                'name' => 'Bull Rush',
                'description' => 'Gordon charge comme un taureau enragé.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'gordon_2' => Attack::create([
                'name' => 'Axe Cleave',
                'description' => 'Un coup de hache dévastateur fend l\'air.',
                'damage' => 125,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'gordon_3' => Attack::create([
                'name' => 'Labyrinth Fury',
                'description' => 'La fureur du gardien du labyrinthe s\'abat.',
                'damage' => 160,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================

        $cards = [
            // === LAIMI DU VER DE TERRE === (Coût 3 - Agile)
            [
                'name' => 'Laimi du Ver de Terre',
                'faction_id' => $factionId,
                'cost' => 3,
                'health_points' => 80,
                'endurance' => 60,
                'defense' => 30,
                'power' => 30,
                'cosmos' => 3,
                'rarity' => 'common',
                'armor_type' => 'surplis',
                'element' => 'earth',
                'main_attack_id' => $attacks['laimi_1']->id,
                'secondary_attack_1_id' => $attacks['laimi_2']->id,
                'secondary_attack_2_id' => $attacks['laimi_3']->id,
            ],

            // === OX DU GORGONE === (Coût 5 - Technique)
            [
                'name' => 'Ox du Gorgone',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 130,
                'endurance' => 70,
                'defense' => 55,
                'power' => 45,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['ox_1']->id,
                'secondary_attack_1_id' => $attacks['ox_2']->id,
                'secondary_attack_2_id' => $attacks['ox_3']->id,
            ],

            // === MILES DE L'ELFE === (Coût 4 - Technique)
            [
                'name' => 'Miles de l\'Elfe',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 100,
                'endurance' => 65,
                'defense' => 40,
                'power' => 42,
                'cosmos' => 4,
                'rarity' => 'common',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['miles_1']->id,
                'secondary_attack_1_id' => $attacks['miles_2']->id,
                'secondary_attack_2_id' => $attacks['miles_3']->id,
            ],

            // === IWAN DU TROLL === (Coût 6 - Tank)
            [
                'name' => 'Iwan du Troll',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 180,
                'endurance' => 75,
                'defense' => 70,
                'power' => 38,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'surplis',
                'element' => 'earth',
                'main_attack_id' => $attacks['iwan_1']->id,
                'secondary_attack_1_id' => $attacks['iwan_2']->id,
                'secondary_attack_2_id' => $attacks['iwan_3']->id,
            ],

            // === ROCK DU GOLEM === (Coût 6 - Tank)
            [
                'name' => 'Rock du Golem',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 190,
                'endurance' => 70,
                'defense' => 75,
                'power' => 32,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'surplis',
                'element' => 'earth',
                'main_attack_id' => $attacks['rock_1']->id,
                'secondary_attack_1_id' => $attacks['rock_2']->id,
                'secondary_attack_2_id' => $attacks['rock_3']->id,
            ],

            // === FLÉGIAS DU LYCAON === (Coût 5 - Équilibré)
            [
                'name' => 'Flégias du Lycaon',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 140,
                'endurance' => 80,
                'defense' => 50,
                'power' => 48,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'surplis',
                'element' => 'water',
                'main_attack_id' => $attacks['flegias_1']->id,
                'secondary_attack_1_id' => $attacks['flegias_2']->id,
                'secondary_attack_2_id' => $attacks['flegias_3']->id,
            ],

            // === STAND DU SCARABÉE MORTEL === (Coût 5 - Bruiser)
            [
                'name' => 'Stand du Scarabée Mortel',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 145,
                'endurance' => 80,
                'defense' => 55,
                'power' => 45,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['stand_1']->id,
                'secondary_attack_1_id' => $attacks['stand_2']->id,
                'secondary_attack_2_id' => $attacks['stand_3']->id,
            ],

            // === MARKINO DU SQUELETTE === (Coût 3 - Équilibré)
            [
                'name' => 'Markino du Squelette',
                'faction_id' => $factionId,
                'cost' => 3,
                'health_points' => 75,
                'endurance' => 55,
                'defense' => 30,
                'power' => 32,
                'cosmos' => 3,
                'rarity' => 'common',
                'armor_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['markino_1']->id,
                'secondary_attack_1_id' => $attacks['markino_2']->id,
                'secondary_attack_2_id' => $attacks['markino_3']->id,
            ],

            // === GORDON DU MINOTAURE === (Coût 7 - Bruiser)
            [
                'name' => 'Gordon du Minotaure',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 185,
                'endurance' => 95,
                'defense' => 65,
                'power' => 62,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'surplis',
                'element' => 'earth',
                'main_attack_id' => $attacks['gordon_1']->id,
                'secondary_attack_1_id' => $attacks['gordon_2']->id,
                'secondary_attack_2_id' => $attacks['gordon_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 9 Spectres secondaires créés avec succès !');
        $this->command->info('✅ 27 Attaques créées avec succès !');
        $this->command->info('💀 Les Spectres d\'Hadès renforcent leurs rangs !');
    }
}

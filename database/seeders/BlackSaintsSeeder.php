<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class BlackSaintsSeeder extends Seeder
{
    /**
     * Seeder pour les Chevaliers Noirs
     * Saint Seiya - Copies corrompues des Chevaliers de Bronze
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Chevaliers Noirs
        $faction = Faction::firstOrCreate(
            ['name' => 'Chevaliers Noirs'],
            [
                'description' => 'Les Chevaliers Noirs sont des guerriers renégats portant des armures corrompues, copies sombres des armures de bronze. Ils servent les forces du mal et n\'obéissent qu\'à leur propre ambition.',
                'color_primary' => '#1F2937',   // Gris foncé
                'color_secondary' => '#7C3AED', // Violet sombre
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === BLACK PEGASUS (Pégase Noir) ===
            'black_pegasus_1' => Attack::create([
                'name' => 'Black Meteor Fist',
                'description' => 'Une version corrompue des Météores de Pégase, imprégnée de ténèbres.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_pegasus_2' => Attack::create([
                'name' => 'Dark Comet Punch',
                'description' => 'Une comète noire qui traverse l\'ennemi.',
                'damage' => 115,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'black_pegasus_3' => Attack::create([
                'name' => 'Shadow Pegasus Rolling Crash',
                'description' => 'Le Pégase des ombres s\'écrase sur sa proie.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BLACK DRAGON (Dragon Noir) ===
            'black_dragon_1' => Attack::create([
                'name' => 'Black Dragon Rising',
                'description' => 'Le dragon noir s\'élève des ténèbres.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_dragon_2' => Attack::create([
                'name' => 'Dark Rozan Sho Ryu Ha',
                'description' => 'La version sombre du Dragon Ascendant de Rozan.',
                'damage' => 130,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'black_dragon_3' => Attack::create([
                'name' => 'Darkness Dragon Supreme',
                'description' => 'Le dragon suprême des ténèbres dévore tout.',
                'damage' => 165,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BLACK CYGNUS (Cygne Noir) ===
            'black_cygnus_1' => Attack::create([
                'name' => 'Black Blizzard',
                'description' => 'Un blizzard noir et glacial.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),
            'black_cygnus_2' => Attack::create([
                'name' => 'Dark Diamond Dust',
                'description' => 'Des cristaux de glace noire qui gèlent l\'âme.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),
            'black_cygnus_3' => Attack::create([
                'name' => 'Shadow Aurora Execution',
                'description' => 'L\'exécution de l\'aurore des ténèbres.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),

            // === BLACK ANDROMEDA (Andromède Noir) ===
            'black_andromeda_1' => Attack::create([
                'name' => 'Black Nebula Chain',
                'description' => 'Les chaînes noires de la nébuleuse capturent l\'ennemi.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'black_andromeda_2' => Attack::create([
                'name' => 'Dark Thunder Wave',
                'description' => 'Une vague de tonnerre sombre parcourt les chaînes.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'black_andromeda_3' => Attack::create([
                'name' => 'Shadow Nebula Storm',
                'description' => 'La tempête de la nébuleuse des ombres.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BLACK PHOENIX (Phénix Noir) ===
            'black_phoenix_1' => Attack::create([
                'name' => 'Black Phoenix Wing',
                'description' => 'Les ailes du phénix noir brûlent de flammes sombres.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'black_phoenix_2' => Attack::create([
                'name' => 'Dark Ho Yoku Ten Sho',
                'description' => 'L\'envol du phénix des ténèbres.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),
            'black_phoenix_3' => Attack::create([
                'name' => 'Phantom Demon Fist',
                'description' => 'Le poing démoniaque fantôme qui brise l\'esprit.',
                'damage' => 175,
                'endurance_cost' => 65,
                'cosmos_cost' => 9,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ]),

            // === JANGO (Chef des Chevaliers Noirs) ===
            'jango_1' => Attack::create([
                'name' => 'Death Punch',
                'description' => 'Un coup mortel sans pitié.',
                'damage' => 85,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'jango_2' => Attack::create([
                'name' => 'Black Saint Command',
                'description' => 'Jango ordonne à ses troupes d\'attaquer.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'buff_attack',
                'effect_value' => 20,
            ]),
            'jango_3' => Attack::create([
                'name' => 'Renegade Execution',
                'description' => 'L\'exécution du chef des renégats.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BLACK UNICORN (Licorne Noir) ===
            'black_unicorn_1' => Attack::create([
                'name' => 'Black Gallop',
                'description' => 'La charge sombre de la licorne noire.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_unicorn_2' => Attack::create([
                'name' => 'Dark Horn Strike',
                'description' => 'La corne noire transperce l\'adversaire.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_unicorn_3' => Attack::create([
                'name' => 'Shadow Unicorn Rampage',
                'description' => 'La licorne des ombres entre en furie.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === BLACK BEAR (Ours Noir) ===
            'black_bear_1' => Attack::create([
                'name' => 'Black Bear Claw',
                'description' => 'Les griffes de l\'ours noir déchirent.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_bear_2' => Attack::create([
                'name' => 'Dark Bear Hug',
                'description' => 'L\'étreinte mortelle de l\'ours des ténèbres.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'black_bear_3' => Attack::create([
                'name' => 'Shadow Bear Rampage',
                'description' => 'L\'ours des ombres se déchaîne.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BLACK LION (Lion Noir) ===
            'black_lion_1' => Attack::create([
                'name' => 'Black Lion Roar',
                'description' => 'Le rugissement du lion noir terrifie.',
                'damage' => 75,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 10,
            ]),
            'black_lion_2' => Attack::create([
                'name' => 'Dark Fang Attack',
                'description' => 'Les crocs sombres du lion attaquent.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_lion_3' => Attack::create([
                'name' => 'Shadow Lion Fury',
                'description' => 'La fureur du lion des ombres.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),

            // === BLACK WOLF (Loup Noir) ===
            'black_wolf_1' => Attack::create([
                'name' => 'Black Fang',
                'description' => 'Les crocs noirs mordent profondément.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'black_wolf_2' => Attack::create([
                'name' => 'Dark Pack Hunt',
                'description' => 'La meute noire encercle sa proie.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_wolf_3' => Attack::create([
                'name' => 'Shadow Wolf Assault',
                'description' => 'L\'assaut du loup des ombres.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),

            // === BLACK SERPENT (Serpent Noir) ===
            'black_serpent_1' => Attack::create([
                'name' => 'Venom Bite',
                'description' => 'Une morsure venimeuse mortelle.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'black_serpent_2' => Attack::create([
                'name' => 'Dark Constriction',
                'description' => 'Le serpent noir étouffe sa victime.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'black_serpent_3' => Attack::create([
                'name' => 'Shadow Serpent Strike',
                'description' => 'La frappe du serpent des ombres.',
                'damage' => 125,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),

            // === BLACK CROW (Corbeau Noir) ===
            'black_crow_1' => Attack::create([
                'name' => 'Black Wing Slash',
                'description' => 'Les ailes noires tranchent comme des lames.',
                'damage' => 60,
                'endurance_cost' => 15,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_crow_2' => Attack::create([
                'name' => 'Dark Feather Storm',
                'description' => 'Une tempête de plumes tranchantes.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_crow_3' => Attack::create([
                'name' => 'Murder of Crows',
                'description' => 'Une nuée de corbeaux dévore l\'ennemi.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),

            // === BLACK SPIDER (Araignée Noire) ===
            'black_spider_1' => Attack::create([
                'name' => 'Web Trap',
                'description' => 'Une toile piège l\'adversaire.',
                'damage' => 55,
                'endurance_cost' => 15,
                'cosmos_cost' => 2,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'black_spider_2' => Attack::create([
                'name' => 'Venom Spray',
                'description' => 'Un jet de venin corrosif.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'black_spider_3' => Attack::create([
                'name' => 'Shadow Arachnid Frenzy',
                'description' => 'La frénésie de l\'araignée des ombres.',
                'damage' => 115,
                'endurance_cost' => 45,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === BLACK SCORPION (Scorpion Noir) ===
            'black_scorpion_1' => Attack::create([
                'name' => 'Stinger Strike',
                'description' => 'Le dard empoisonné frappe.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'black_scorpion_2' => Attack::create([
                'name' => 'Dark Pincer Crush',
                'description' => 'Les pinces sombres broient l\'ennemi.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_scorpion_3' => Attack::create([
                'name' => 'Shadow Scarlet Needle',
                'description' => 'L\'aiguille écarlate des ombres.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 25,
            ]),

            // === BLACK HYDRA (Hydre Noire) ===
            'black_hydra_1' => Attack::create([
                'name' => 'Poison Fang',
                'description' => 'Un croc empoisonné de l\'hydre.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'black_hydra_2' => Attack::create([
                'name' => 'Multi-Head Attack',
                'description' => 'Les multiples têtes attaquent ensemble.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'black_hydra_3' => Attack::create([
                'name' => 'Shadow Hydra Venom',
                'description' => 'Le venin mortel de l\'hydre des ombres.',
                'damage' => 125,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 30,
            ]),

            // === DARK LORD (Seigneur des Ténèbres - Boss) ===
            'dark_lord_1' => Attack::create([
                'name' => 'Darkness Aura',
                'description' => 'Une aura de ténèbres enveloppe le champ de bataille.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),
            'dark_lord_2' => Attack::create([
                'name' => 'Shadow Realm',
                'description' => 'Le royaume des ombres s\'étend.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ]),
            'dark_lord_3' => Attack::create([
                'name' => 'Eternal Darkness',
                'description' => 'Les ténèbres éternelles engloutissent tout.',
                'damage' => 200,
                'endurance_cost' => 75,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === SHADOW MASTER (Maître des Ombres) ===
            'shadow_master_1' => Attack::create([
                'name' => 'Shadow Bind',
                'description' => 'Les ombres lient et paralysent.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'shadow_master_2' => Attack::create([
                'name' => 'Nightmare Illusion',
                'description' => 'Une illusion cauchemardesque terrifie l\'ennemi.',
                'damage' => 115,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ]),
            'shadow_master_3' => Attack::create([
                'name' => 'Void Embrace',
                'description' => 'L\'étreinte du vide absorbe la vie.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'drain',
                'effect_value' => 30,
            ]),

            // === DARK ASSASSIN (Assassin des Ténèbres) ===
            'dark_assassin_1' => Attack::create([
                'name' => 'Silent Kill',
                'description' => 'Une attaque silencieuse et mortelle.',
                'damage' => 90,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'dark_assassin_2' => Attack::create([
                'name' => 'Backstab',
                'description' => 'Un coup dans le dos traître.',
                'damage' => 130,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'dark_assassin_3' => Attack::create([
                'name' => 'Death Mark',
                'description' => 'La marque de la mort condamne la cible.',
                'damage' => 160,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================

        $cards = [
            // === BLACK PEGASUS === (Coût 6 - Attaquant)
            [
                'name' => 'Pégase Noir',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 145,
                'endurance' => 90,
                'defense' => 45,
                'power' => 65,
                'cosmos' => 6,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_pegasus_1']->id,
                'secondary_attack_1_id' => $attacks['black_pegasus_2']->id,
                'secondary_attack_2_id' => $attacks['black_pegasus_3']->id,
            ],

            // === BLACK DRAGON === (Coût 7 - Bruiser)
            [
                'name' => 'Dragon Noir',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 180,
                'endurance' => 95,
                'defense' => 65,
                'power' => 60,
                'cosmos' => 7,
                'rarity' => 'epic',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_dragon_1']->id,
                'secondary_attack_1_id' => $attacks['black_dragon_2']->id,
                'secondary_attack_2_id' => $attacks['black_dragon_3']->id,
            ],

            // === BLACK CYGNUS === (Coût 6 - Technique)
            [
                'name' => 'Cygne Noir',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 150,
                'endurance' => 75,
                'defense' => 70,
                'power' => 48,
                'cosmos' => 6,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'ice',
                'main_attack_id' => $attacks['black_cygnus_1']->id,
                'secondary_attack_1_id' => $attacks['black_cygnus_2']->id,
                'secondary_attack_2_id' => $attacks['black_cygnus_3']->id,
            ],

            // === BLACK ANDROMEDA === (Coût 6 - Technique)
            [
                'name' => 'Andromède Noir',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 145,
                'endurance' => 70,
                'defense' => 75,
                'power' => 45,
                'cosmos' => 6,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_andromeda_1']->id,
                'secondary_attack_1_id' => $attacks['black_andromeda_2']->id,
                'secondary_attack_2_id' => $attacks['black_andromeda_3']->id,
            ],

            // === BLACK PHOENIX === (Coût 8 - Attaquant)
            [
                'name' => 'Phénix Noir',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 185,
                'endurance' => 110,
                'defense' => 55,
                'power' => 90,
                'cosmos' => 8,
                'rarity' => 'epic',
                'power_type' => 'bronze',
                'element' => 'fire',
                'main_attack_id' => $attacks['black_phoenix_1']->id,
                'secondary_attack_1_id' => $attacks['black_phoenix_2']->id,
                'secondary_attack_2_id' => $attacks['black_phoenix_3']->id,
            ],

            // === JANGO === (Coût 7 - Équilibré - Chef)
            [
                'name' => 'Jango',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 175,
                'endurance' => 95,
                'defense' => 60,
                'power' => 65,
                'cosmos' => 7,
                'rarity' => 'epic',
                'power_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['jango_1']->id,
                'secondary_attack_1_id' => $attacks['jango_2']->id,
                'secondary_attack_2_id' => $attacks['jango_3']->id,
            ],

            // === BLACK UNICORN === (Coût 5 - Équilibré)
            [
                'name' => 'Licorne Noir',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 135,
                'endurance' => 80,
                'defense' => 50,
                'power' => 48,
                'cosmos' => 5,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_unicorn_1']->id,
                'secondary_attack_1_id' => $attacks['black_unicorn_2']->id,
                'secondary_attack_2_id' => $attacks['black_unicorn_3']->id,
            ],

            // === BLACK BEAR === (Coût 6 - Tank)
            [
                'name' => 'Ours Noir',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 185,
                'endurance' => 75,
                'defense' => 70,
                'power' => 35,
                'cosmos' => 6,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'earth',
                'main_attack_id' => $attacks['black_bear_1']->id,
                'secondary_attack_1_id' => $attacks['black_bear_2']->id,
                'secondary_attack_2_id' => $attacks['black_bear_3']->id,
            ],

            // === BLACK LION === (Coût 5 - Bruiser)
            [
                'name' => 'Lion Noir',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 145,
                'endurance' => 80,
                'defense' => 50,
                'power' => 50,
                'cosmos' => 5,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'fire',
                'main_attack_id' => $attacks['black_lion_1']->id,
                'secondary_attack_1_id' => $attacks['black_lion_2']->id,
                'secondary_attack_2_id' => $attacks['black_lion_3']->id,
            ],

            // === BLACK WOLF === (Coût 4 - Agile)
            [
                'name' => 'Loup Noir',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 105,
                'endurance' => 75,
                'defense' => 35,
                'power' => 45,
                'cosmos' => 4,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_wolf_1']->id,
                'secondary_attack_1_id' => $attacks['black_wolf_2']->id,
                'secondary_attack_2_id' => $attacks['black_wolf_3']->id,
            ],

            // === BLACK SERPENT === (Coût 4 - Technique)
            [
                'name' => 'Serpent Noir',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 100,
                'endurance' => 65,
                'defense' => 45,
                'power' => 40,
                'cosmos' => 4,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_serpent_1']->id,
                'secondary_attack_1_id' => $attacks['black_serpent_2']->id,
                'secondary_attack_2_id' => $attacks['black_serpent_3']->id,
            ],

            // === BLACK CROW === (Coût 3 - Agile)
            [
                'name' => 'Corbeau Noir',
                'faction_id' => $factionId,
                'cost' => 3,
                'health_points' => 80,
                'endurance' => 60,
                'defense' => 25,
                'power' => 35,
                'cosmos' => 3,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_crow_1']->id,
                'secondary_attack_1_id' => $attacks['black_crow_2']->id,
                'secondary_attack_2_id' => $attacks['black_crow_3']->id,
            ],

            // === BLACK SPIDER === (Coût 3 - Technique)
            [
                'name' => 'Araignée Noire',
                'faction_id' => $factionId,
                'cost' => 3,
                'health_points' => 75,
                'endurance' => 55,
                'defense' => 30,
                'power' => 32,
                'cosmos' => 3,
                'rarity' => 'common',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_spider_1']->id,
                'secondary_attack_1_id' => $attacks['black_spider_2']->id,
                'secondary_attack_2_id' => $attacks['black_spider_3']->id,
            ],

            // === BLACK SCORPION === (Coût 5 - Attaquant)
            [
                'name' => 'Scorpion Noir',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 125,
                'endurance' => 85,
                'defense' => 40,
                'power' => 55,
                'cosmos' => 5,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_scorpion_1']->id,
                'secondary_attack_1_id' => $attacks['black_scorpion_2']->id,
                'secondary_attack_2_id' => $attacks['black_scorpion_3']->id,
            ],

            // === BLACK HYDRA === (Coût 5 - Technique)
            [
                'name' => 'Hydre Noire',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 130,
                'endurance' => 70,
                'defense' => 55,
                'power' => 45,
                'cosmos' => 5,
                'rarity' => 'rare',
                'power_type' => 'bronze',
                'element' => 'darkness',
                'main_attack_id' => $attacks['black_hydra_1']->id,
                'secondary_attack_1_id' => $attacks['black_hydra_2']->id,
                'secondary_attack_2_id' => $attacks['black_hydra_3']->id,
            ],

            // === DARK LORD === (Coût 9 - Technique - Boss)
            [
                'name' => 'Seigneur des Ténèbres',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 230,
                'endurance' => 100,
                'defense' => 110,
                'power' => 75,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'power_type' => 'surplis',
                'element' => 'darkness',
                'main_attack_id' => $attacks['dark_lord_1']->id,
                'secondary_attack_1_id' => $attacks['dark_lord_2']->id,
                'secondary_attack_2_id' => $attacks['dark_lord_3']->id,
            ],

            // === SHADOW MASTER === (Coût 7 - Technique)
            [
                'name' => 'Maître des Ombres',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 170,
                'endurance' => 85,
                'defense' => 80,
                'power' => 55,
                'cosmos' => 7,
                'rarity' => 'epic',
                'power_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['shadow_master_1']->id,
                'secondary_attack_1_id' => $attacks['shadow_master_2']->id,
                'secondary_attack_2_id' => $attacks['shadow_master_3']->id,
            ],

            // === DARK ASSASSIN === (Coût 6 - Agile)
            [
                'name' => 'Assassin des Ténèbres',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 130,
                'endurance' => 100,
                'defense' => 35,
                'power' => 72,
                'cosmos' => 6,
                'rarity' => 'rare',
                'power_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['dark_assassin_1']->id,
                'secondary_attack_1_id' => $attacks['dark_assassin_2']->id,
                'secondary_attack_2_id' => $attacks['dark_assassin_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 18 Chevaliers Noirs créés avec succès !');
        $this->command->info('✅ 54 Attaques créées avec succès !');
        $this->command->info('🖤 Faction: Chevaliers Noirs');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class PoseidonMarinasSeeder extends Seeder
{
    /**
     * Seeder pour les Marinas de Poséidon
     * Saint Seiya - Saga Poséidon
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Marinas
        $faction = Faction::firstOrCreate(
            ['name' => 'Marinas de Poséidon'],
            [
                'description' => 'Les Marinas sont les guerriers sacrés du dieu des mers Poséidon. Ils portent les Écailles (Scale) et protègent les Sept Piliers des Océans.',
                'color_primary' => '#0EA5E9',   // Bleu océan
                'color_secondary' => '#164E63', // Bleu profond
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === POSÉIDON / JULIAN SOLO ===
            'poseidon_1' => Attack::create([
                'name' => 'Divine Trident',
                'description' => 'Poséidon frappe avec son trident divin, arme légendaire des mers.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'poseidon_2' => Attack::create([
                'name' => 'Ocean\'s Wrath',
                'description' => 'La colère des océans se déchaîne sur les ennemis.',
                'damage' => 160,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'poseidon_3' => Attack::create([
                'name' => 'God\'s Flood',
                'description' => 'Le déluge divin qui menace d\'engloutir le monde entier.',
                'damage' => 220,
                'endurance_cost' => 80,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === KANON DE SEA DRAGON (Dragon des Mers) ===
            'kanon_1' => Attack::create([
                'name' => 'Golden Triangle',
                'description' => 'Kanon ouvre une dimension triangulaire qui emprisonne l\'ennemi.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'kanon_2' => Attack::create([
                'name' => 'Galaxian Explosion',
                'description' => 'L\'explosion galactique, technique jumelle de celle de Saga.',
                'damage' => 170,
                'endurance_cost' => 60,
                'cosmos_cost' => 9,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'kanon_3' => Attack::create([
                'name' => 'Another Dimension',
                'description' => 'Kanon ouvre un portail vers une autre dimension.',
                'damage' => 200,
                'endurance_cost' => 75,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === SORRENTO DE SIREN ===
            'sorrento_1' => Attack::create([
                'name' => 'Dead End Symphony',
                'description' => 'Une mélodie mortelle jouée à la flûte qui détruit les sens.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'sorrento_2' => Attack::create([
                'name' => 'Dead End Climax',
                'description' => 'Le point culminant de la symphonie mortelle.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'sorrento_3' => Attack::create([
                'name' => 'Siren\'s Requiem',
                'description' => 'Le requiem final qui guide les âmes vers les profondeurs.',
                'damage' => 175,
                'endurance_cost' => 65,
                'cosmos_cost' => 9,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ]),

            // === KRISHNA DE CHRYSAOR ===
            'krishna_1' => Attack::create([
                'name' => 'Maha Roshini',
                'description' => 'La grande lumière qui illumine et brûle.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'krishna_2' => Attack::create([
                'name' => 'Flashing Lancer',
                'description' => 'Krishna projette sa lance dorée à une vitesse fulgurante.',
                'damage' => 130,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'krishna_3' => Attack::create([
                'name' => 'Golden Lance Eruption',
                'description' => 'L\'éruption de la lance dorée de Chrysaor.',
                'damage' => 165,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === IO DE SCYLLA ===
            'io_1' => Attack::create([
                'name' => 'Eagle Clutch',
                'description' => 'L\'aigle de Scylla fond sur sa proie.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'io_2' => Attack::create([
                'name' => 'Wolf Fang',
                'description' => 'Les crocs du loup de Scylla déchirent l\'ennemi.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'io_3' => Attack::create([
                'name' => 'Big Tornado',
                'description' => 'Une gigantesque tornade créée par les six bêtes de Scylla.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BAIAN DE SEA HORSE (Hippocampe) ===
            'baian_1' => Attack::create([
                'name' => 'Rising Billows',
                'description' => 'Des vagues montantes submergent l\'adversaire.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'baian_2' => Attack::create([
                'name' => 'God Breath',
                'description' => 'Le souffle divin de l\'Hippocampe.',
                'damage' => 120,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),
            'baian_3' => Attack::create([
                'name' => 'Hippocampus Tidal Wave',
                'description' => 'Un raz-de-marée dévastateur invoqué par l\'Hippocampe.',
                'damage' => 155,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === KASA DE LYMNADES ===
            'kasa_1' => Attack::create([
                'name' => 'Salamander Shock',
                'description' => 'Le choc électrique de la salamandre.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'kasa_2' => Attack::create([
                'name' => 'Ghost Transformation',
                'description' => 'Kasa prend l\'apparence de quelqu\'un d\'autre pour tromper.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),
            'kasa_3' => Attack::create([
                'name' => 'Lymnades Illusion',
                'description' => 'Une illusion parfaite qui manipule l\'esprit de l\'ennemi.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ]),

            // === ISAAC DE KRAKEN ===
            'isaac_1' => Attack::create([
                'name' => 'Aurora Borealis',
                'description' => 'L\'aurore boréale, technique apprise auprès de Camus.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),
            'isaac_2' => Attack::create([
                'name' => 'Kraken Tentacles',
                'description' => 'Les tentacules du Kraken enserrent et broient.',
                'damage' => 125,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'isaac_3' => Attack::create([
                'name' => 'Northern Ocean Storm',
                'description' => 'La tempête glaciale de l\'océan arctique.',
                'damage' => 160,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ]),

            // === THÉTIS DE MERMAID (Sirène) ===
            'thetis_1' => Attack::create([
                'name' => 'Coral Rush',
                'description' => 'Une rafale de coraux tranchants.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'thetis_2' => Attack::create([
                'name' => 'Ocean\'s Embrace',
                'description' => 'L\'étreinte protectrice de l\'océan soigne les alliés.',
                'damage' => 80,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'heal',
                'effect_value' => 30,
            ]),
            'thetis_3' => Attack::create([
                'name' => 'Mermaid\'s Blessing',
                'description' => 'La bénédiction de la sirène renforce les défenses.',
                'damage' => 100,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'buff_defense',
                'effect_value' => 25,
            ]),

            // === CAÇA (général secondaire) ===
            'caca_1' => Attack::create([
                'name' => 'Depth Pressure',
                'description' => 'La pression des abysses écrase l\'ennemi.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'debuff',
                'effect_value' => 10,
            ]),
            'caca_2' => Attack::create([
                'name' => 'Abyssal Current',
                'description' => 'Un courant sous-marin puissant emporte l\'adversaire.',
                'damage' => 105,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'caca_3' => Attack::create([
                'name' => 'Deep Sea Vortex',
                'description' => 'Un vortex des profondeurs aspire tout.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === TRITON (garde de Poséidon) ===
            'triton_1' => Attack::create([
                'name' => 'Conch Shell Blast',
                'description' => 'Le son de la conque de Triton paralyse.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'triton_2' => Attack::create([
                'name' => 'Trident Strike',
                'description' => 'Un coup de trident précis et mortel.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'triton_3' => Attack::create([
                'name' => 'Herald of the Seas',
                'description' => 'Triton invoque la puissance de son père Poséidon.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'buff_attack',
                'effect_value' => 15,
            ]),

            // === NÉRÉE (ancien dieu marin) ===
            'neree_1' => Attack::create([
                'name' => 'Ancient Tide',
                'description' => 'Les marées anciennes répondent à l\'appel de Nérée.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'heal',
                'effect_value' => 20,
            ]),
            'neree_2' => Attack::create([
                'name' => 'Prophecy of the Deep',
                'description' => 'Nérée prédit et évite les attaques ennemies.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'buff_defense',
                'effect_value' => 30,
            ]),
            'neree_3' => Attack::create([
                'name' => 'Primordial Ocean',
                'description' => 'L\'océan primordial engloutit tout sur son passage.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === OCÉANOS ===
            'oceanos_1' => Attack::create([
                'name' => 'Titan\'s Wave',
                'description' => 'La vague du Titan Océanos.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'oceanos_2' => Attack::create([
                'name' => 'World River',
                'description' => 'Le fleuve qui encercle le monde se déchaîne.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'oceanos_3' => Attack::create([
                'name' => 'Titan\'s Deluge',
                'description' => 'Le déluge titanesque qui précède celui de Poséidon.',
                'damage' => 170,
                'endurance_cost' => 65,
                'cosmos_cost' => 9,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === PROTÉE ===
            'protee_1' => Attack::create([
                'name' => 'Shape Shift',
                'description' => 'Protée change de forme pour surprendre.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'protee_2' => Attack::create([
                'name' => 'Seal\'s Fury',
                'description' => 'La fureur du gardien des phoques de Poséidon.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'protee_3' => Attack::create([
                'name' => 'Thousand Forms',
                'description' => 'Protée attaque sous mille formes différentes.',
                'damage' => 125,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),

            // === AMPHITRITE ===
            'amphitrite_1' => Attack::create([
                'name' => 'Queen\'s Grace',
                'description' => 'La grâce de la reine des mers protège.',
                'damage' => 60,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'heal',
                'effect_value' => 25,
            ]),
            'amphitrite_2' => Attack::create([
                'name' => 'Dolphin Escort',
                'description' => 'Les dauphins d\'Amphitrite attaquent en formation.',
                'damage' => 90,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'amphitrite_3' => Attack::create([
                'name' => 'Sovereign of the Seas',
                'description' => 'Amphitrite déploie toute sa puissance de souveraine.',
                'damage' => 120,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'buff_defense',
                'effect_value' => 30,
            ]),

            // === SCYLLA (monstre marin) ===
            'scylla_1' => Attack::create([
                'name' => 'Serpent Heads',
                'description' => 'Les têtes de serpent de Scylla mordent.',
                'damage' => 85,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'scylla_2' => Attack::create([
                'name' => 'Monster\'s Grasp',
                'description' => 'Scylla attrape et dévore les marins.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'drain',
                'effect_value' => 20,
            ]),
            'scylla_3' => Attack::create([
                'name' => 'Six-Headed Assault',
                'description' => 'Les six têtes de Scylla attaquent simultanément.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === CHARYBDE ===
            'charybde_1' => Attack::create([
                'name' => 'Whirlpool',
                'description' => 'Charybde crée un tourbillon dévastateur.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'charybde_2' => Attack::create([
                'name' => 'Devouring Depths',
                'description' => 'Les profondeurs dévorantes de Charybde.',
                'damage' => 115,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'drain',
                'effect_value' => 25,
            ]),
            'charybde_3' => Attack::create([
                'name' => 'Abyssal Maw',
                'description' => 'La gueule abyssale engloutit tout.',
                'damage' => 150,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === LEVIATHAN ===
            'leviathan_1' => Attack::create([
                'name' => 'Sea Serpent Strike',
                'description' => 'Le serpent de mer frappe avec sa queue.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'leviathan_2' => Attack::create([
                'name' => 'Chaos of the Deep',
                'description' => 'Le chaos des profondeurs se répand.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),
            'leviathan_3' => Attack::create([
                'name' => 'Primordial Beast',
                'description' => 'Le Léviathan révèle sa forme primordiale.',
                'damage' => 180,
                'endurance_cost' => 70,
                'cosmos_cost' => 9,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === CETO ===
            'ceto_1' => Attack::create([
                'name' => 'Sea Monster Call',
                'description' => 'Céto appelle les monstres marins.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'buff_attack',
                'effect_value' => 10,
            ]),
            'ceto_2' => Attack::create([
                'name' => 'Mother of Monsters',
                'description' => 'La mère des monstres déchaîne sa progéniture.',
                'damage' => 100,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'ceto_3' => Attack::create([
                'name' => 'Primordial Terror',
                'description' => 'La terreur primordiale des océans.',
                'damage' => 135,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================

        $cards = [
            // === POSÉIDON / JULIAN SOLO === (Coût 10 - Technique - Dieu des Mers)
            [
                'name' => 'Poséidon',
                'faction_id' => $factionId,
                'cost' => 10,
                'health_points' => 270,
                'endurance' => 120,
                'defense' => 130,
                'power' => 80,
                'cosmos' => 10,
                'rarity' => 'mythic',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['poseidon_1']->id,
                'secondary_attack_1_id' => $attacks['poseidon_2']->id,
                'secondary_attack_2_id' => $attacks['poseidon_3']->id,
            ],

            // === KANON DE SEA DRAGON === (Coût 9 - Attaquant - Général en chef)
            [
                'name' => 'Kanon de Dragon des Mers',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 195,
                'endurance' => 125,
                'defense' => 60,
                'power' => 115,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['kanon_1']->id,
                'secondary_attack_1_id' => $attacks['kanon_2']->id,
                'secondary_attack_2_id' => $attacks['kanon_3']->id,
            ],

            // === SORRENTO DE SIREN === (Coût 8 - Technique - Musicien mortel)
            [
                'name' => 'Sorrento de Siren',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 200,
                'endurance' => 90,
                'defense' => 95,
                'power' => 65,
                'cosmos' => 8,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['sorrento_1']->id,
                'secondary_attack_1_id' => $attacks['sorrento_2']->id,
                'secondary_attack_2_id' => $attacks['sorrento_3']->id,
            ],

            // === KRISHNA DE CHRYSAOR === (Coût 8 - Bruiser - Guerrier à la lance)
            [
                'name' => 'Krishna de Chrysaor',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 210,
                'endurance' => 100,
                'defense' => 80,
                'power' => 70,
                'cosmos' => 8,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'light',
                'main_attack_id' => $attacks['krishna_1']->id,
                'secondary_attack_1_id' => $attacks['krishna_2']->id,
                'secondary_attack_2_id' => $attacks['krishna_3']->id,
            ],

            // === IO DE SCYLLA === (Coût 7 - Équilibré - Six bêtes)
            [
                'name' => 'Io de Scylla',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 175,
                'endurance' => 95,
                'defense' => 65,
                'power' => 60,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['io_1']->id,
                'secondary_attack_1_id' => $attacks['io_2']->id,
                'secondary_attack_2_id' => $attacks['io_3']->id,
            ],

            // === BAIAN DE SEA HORSE === (Coût 7 - Bruiser)
            [
                'name' => 'Baian de Hippocampe',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 190,
                'endurance' => 90,
                'defense' => 70,
                'power' => 55,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['baian_1']->id,
                'secondary_attack_1_id' => $attacks['baian_2']->id,
                'secondary_attack_2_id' => $attacks['baian_3']->id,
            ],

            // === KASA DE LYMNADES === (Coût 6 - Technique - Illusionniste)
            [
                'name' => 'Kasa de Lymnades',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 155,
                'endurance' => 70,
                'defense' => 70,
                'power' => 45,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['kasa_1']->id,
                'secondary_attack_1_id' => $attacks['kasa_2']->id,
                'secondary_attack_2_id' => $attacks['kasa_3']->id,
            ],

            // === ISAAC DE KRAKEN === (Coût 7 - Équilibré - Glace)
            [
                'name' => 'Isaac de Kraken',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 180,
                'endurance' => 95,
                'defense' => 70,
                'power' => 62,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'ice',
                'main_attack_id' => $attacks['isaac_1']->id,
                'secondary_attack_1_id' => $attacks['isaac_2']->id,
                'secondary_attack_2_id' => $attacks['isaac_3']->id,
            ],

            // === THÉTIS DE MERMAID === (Coût 5 - Technique - Support)
            [
                'name' => 'Thétis de Mermaid',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 130,
                'endurance' => 65,
                'defense' => 55,
                'power' => 35,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['thetis_1']->id,
                'secondary_attack_1_id' => $attacks['thetis_2']->id,
                'secondary_attack_2_id' => $attacks['thetis_3']->id,
            ],

            // === CAÇA === (Coût 5 - Équilibré)
            [
                'name' => 'Caça',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 140,
                'endurance' => 80,
                'defense' => 50,
                'power' => 45,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'water',
                'main_attack_id' => $attacks['caca_1']->id,
                'secondary_attack_1_id' => $attacks['caca_2']->id,
                'secondary_attack_2_id' => $attacks['caca_3']->id,
            ],

            // === TRITON === (Coût 6 - Équilibré - Héraut)
            [
                'name' => 'Triton',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 165,
                'endurance' => 85,
                'defense' => 60,
                'power' => 52,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['triton_1']->id,
                'secondary_attack_1_id' => $attacks['triton_2']->id,
                'secondary_attack_2_id' => $attacks['triton_3']->id,
            ],

            // === NÉRÉE === (Coût 6 - Technique - Ancien sage)
            [
                'name' => 'Nérée',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 160,
                'endurance' => 70,
                'defense' => 80,
                'power' => 40,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['neree_1']->id,
                'secondary_attack_1_id' => $attacks['neree_2']->id,
                'secondary_attack_2_id' => $attacks['neree_3']->id,
            ],

            // === OCÉANOS === (Coût 8 - Tank - Titan)
            [
                'name' => 'Océanos',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 250,
                'endurance' => 85,
                'defense' => 95,
                'power' => 45,
                'cosmos' => 8,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['oceanos_1']->id,
                'secondary_attack_1_id' => $attacks['oceanos_2']->id,
                'secondary_attack_2_id' => $attacks['oceanos_3']->id,
            ],

            // === PROTÉE === (Coût 5 - Agile - Métamorphe)
            [
                'name' => 'Protée',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 125,
                'endurance' => 90,
                'defense' => 40,
                'power' => 48,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'water',
                'main_attack_id' => $attacks['protee_1']->id,
                'secondary_attack_1_id' => $attacks['protee_2']->id,
                'secondary_attack_2_id' => $attacks['protee_3']->id,
            ],

            // === AMPHITRITE === (Coût 7 - Technique - Reine)
            [
                'name' => 'Amphitrite',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 175,
                'endurance' => 75,
                'defense' => 85,
                'power' => 50,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'water',
                'main_attack_id' => $attacks['amphitrite_1']->id,
                'secondary_attack_1_id' => $attacks['amphitrite_2']->id,
                'secondary_attack_2_id' => $attacks['amphitrite_3']->id,
            ],

            // === SCYLLA (monstre) === (Coût 7 - Attaquant - Monstre)
            [
                'name' => 'Scylla',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 160,
                'endurance' => 100,
                'defense' => 50,
                'power' => 78,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['scylla_1']->id,
                'secondary_attack_1_id' => $attacks['scylla_2']->id,
                'secondary_attack_2_id' => $attacks['scylla_3']->id,
            ],

            // === CHARYBDE === (Coût 6 - Tank - Monstre)
            [
                'name' => 'Charybde',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 185,
                'endurance' => 70,
                'defense' => 75,
                'power' => 38,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['charybde_1']->id,
                'secondary_attack_1_id' => $attacks['charybde_2']->id,
                'secondary_attack_2_id' => $attacks['charybde_3']->id,
            ],

            // === LEVIATHAN === (Coût 8 - Attaquant - Bête primordiale)
            [
                'name' => 'Léviathan',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 195,
                'endurance' => 105,
                'defense' => 55,
                'power' => 90,
                'cosmos' => 8,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['leviathan_1']->id,
                'secondary_attack_1_id' => $attacks['leviathan_2']->id,
                'secondary_attack_2_id' => $attacks['leviathan_3']->id,
            ],

            // === CETO === (Coût 5 - Technique - Mère des monstres)
            [
                'name' => 'Céto',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 135,
                'endurance' => 65,
                'defense' => 60,
                'power' => 40,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['ceto_1']->id,
                'secondary_attack_1_id' => $attacks['ceto_2']->id,
                'secondary_attack_2_id' => $attacks['ceto_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 19 Marinas de Poséidon créés avec succès !');
        $this->command->info('✅ 57 Attaques créées avec succès !');
        $this->command->info('🔱 Faction: Marinas de Poséidon');
    }
}

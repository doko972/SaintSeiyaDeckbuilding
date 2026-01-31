<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class AsgardWarriorsSeeder extends Seeder
{
    /**
     * Seeder pour les Guerriers Divins d'Asgard
     * Saint Seiya - Saga d'Asgard + Soul of Gold
     * 
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Asgard
        $faction = Faction::firstOrCreate(
            ['name' => 'Guerriers Divins d\'Asgard'],
            [
                'description' => 'Les Guerriers Divins d\'Asgard, protecteurs du royaume nordique sous les ordres d\'Hilda de Polaris. Ils portent les Robes Divines et combattent avec la force des dieux nordiques.',
                'color_primary' => '#60A5FA',   // Bleu glacé
                'color_secondary' => '#1E3A5F', // Bleu foncé
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================
        
        $attacks = [
            // === HILDA DE POLARIS ===
            'hilda_1' => Attack::create([
                'name' => 'Odin\'s Light',
                'description' => 'Hilda canalise la lumière divine d\'Odin pour protéger ses guerriers.',
                'damage' => 80,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'heal',
                'effect_value' => 30,
            ]),
            'hilda_2' => Attack::create([
                'name' => 'Nibelung Blessing',
                'description' => 'Bénédiction de l\'anneau des Nibelungen, augmente la puissance alliée.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 7,
                'effect_type' => 'buff_attack',
                'effect_value' => 20,
            ]),
            'hilda_3' => Attack::create([
                'name' => 'Odin\'s Wrath',
                'description' => 'La colère d\'Odin s\'abat sur les ennemis d\'Asgard.',
                'damage' => 180,
                'endurance_cost' => 70,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === SIEGFRIED DE DUBHE (Alpha) ===
            'siegfried_1' => Attack::create([
                'name' => 'Dragon Bravest Blizzard',
                'description' => 'Siegfried déchaîne un blizzard destructeur avec la puissance du dragon.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'siegfried_2' => Attack::create([
                'name' => 'Odin Sword',
                'description' => 'L\'épée légendaire d\'Odin tranche tout sur son passage.',
                'damage' => 160,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'siegfried_3' => Attack::create([
                'name' => 'Nibelung Valesti',
                'description' => 'La technique ultime de Siegfried, capable de rivaliser avec Excalibur.',
                'damage' => 220,
                'endurance_cost' => 80,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === THOR DE PHECDA (Gamma) ===
            'thor_1' => Attack::create([
                'name' => 'Mjolnir Hammer',
                'description' => 'Thor frappe avec la puissance de son marteau légendaire.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'thor_2' => Attack::create([
                'name' => 'Titanic Hercules',
                'description' => 'Une attaque titanesque digne d\'Hercule lui-même.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'thor_3' => Attack::create([
                'name' => 'Thunder God Crush',
                'description' => 'La fureur du dieu du tonnerre écrase tout adversaire.',
                'damage' => 190,
                'endurance_cost' => 70,
                'cosmos_cost' => 9,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === FENRIR DE ALIOTH (Epsilon) ===
            'fenrir_1' => Attack::create([
                'name' => 'Wolf Cruelty Claw',
                'description' => 'Fenrir attaque avec la férocité de ses loups.',
                'damage' => 85,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'fenrir_2' => Attack::create([
                'name' => 'Northern Gungnir',
                'description' => 'La lance d\'Odin guidée par l\'instinct du loup.',
                'damage' => 130,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'fenrir_3' => Attack::create([
                'name' => 'Wolves Hunting Pack',
                'description' => 'La meute de loups encercle et dévore l\'ennemi.',
                'damage' => 170,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 3,
            ]),

            // === HAGEN DE MERAK (Beta) ===
            'hagen_1' => Attack::create([
                'name' => 'Greatest Ardor',
                'description' => 'Hagen libère une vague de chaleur intense.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'hagen_2' => Attack::create([
                'name' => 'Flame Sword',
                'description' => 'L\'épée de feu tranche avec une chaleur dévastatrice.',
                'damage' => 120,
                'endurance_cost' => 40,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),
            'hagen_3' => Attack::create([
                'name' => 'Merak Inferno',
                'description' => 'Un inferno nordique qui consume tout.',
                'damage' => 160,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === MIME DE BENETNASCH (Eta) ===
            'mime_1' => Attack::create([
                'name' => 'Stringer Requiem',
                'description' => 'Les cordes de Mime créent une mélodie mortelle.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'debuff',
                'effect_value' => 1,
            ]),
            'mime_2' => Attack::create([
                'name' => 'Phantom Strings',
                'description' => 'Des fils invisibles qui tranchent et paralysent.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'mime_3' => Attack::create([
                'name' => 'Death Trip Serenade',
                'description' => 'Une sérénade mortelle qui guide l\'âme vers le Valhalla.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === ALBERICH DE MEGREZ (Delta) ===
            'alberich_1' => Attack::create([
                'name' => 'Amethyst Shield',
                'description' => 'Un bouclier d\'améthyste protège Alberich.',
                'damage' => 60,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'buff_defense',
                'effect_value' => 30,
            ]),
            'alberich_2' => Attack::create([
                'name' => 'Nature Unity',
                'description' => 'Alberich fusionne avec la nature pour attaquer.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'heal',
                'effect_value' => 20,
            ]),
            'alberich_3' => Attack::create([
                'name' => 'Sword of Flames of Balmung',
                'description' => 'L\'épée légendaire Balmung embrasée de flammes.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'burn',
                'effect_value' => 25,
            ]),

            // === SHIDO DE MIZAR (Zeta) ===
            'shido_1' => Attack::create([
                'name' => 'Blue Impulse',
                'description' => 'Une impulsion d\'énergie bleue glaciale.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'debuff',
                'effect_value' => 1,
            ]),
            'shido_2' => Attack::create([
                'name' => 'Twin Shadow Strike',
                'description' => 'Shido et son ombre jumelle frappent simultanément.',
                'damage' => 120,
                'endurance_cost' => 40,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 2,
            ]),
            'shido_3' => Attack::create([
                'name' => 'Mizar Blue Flash',
                'description' => 'L\'éclair bleu de Mizar illumine le champ de bataille.',
                'damage' => 155,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === BADO D'ALCOR (Zeta Shadow) ===
            'bado_1' => Attack::create([
                'name' => 'Shadow Viking Tiger Claw',
                'description' => 'Les griffes du tigre des ombres déchirent l\'ennemi.',
                'damage' => 85,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'bado_2' => Attack::create([
                'name' => 'Dark Twin Assault',
                'description' => 'Bado surgit des ténèbres pour une attaque double.',
                'damage' => 125,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'bado_3' => Attack::create([
                'name' => 'Alcor Shadow Execution',
                'description' => 'L\'exécution depuis les ombres d\'Alcor.',
                'damage' => 160,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 50,
            ]),

            // === FRODI (Soul of Gold) ===
            'frodi_1' => Attack::create([
                'name' => 'Frost Blade',
                'description' => 'Une lame de givre tranchante.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 1,
            ]),
            'frodi_2' => Attack::create([
                'name' => 'Gullinbursti Charge',
                'description' => 'Frodi charge avec la force du sanglier doré.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'frodi_3' => Attack::create([
                'name' => 'Yggdrasil Judgement',
                'description' => 'Le jugement de l\'arbre monde s\'abat sur l\'ennemi.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === HERCULES DE TANNGRISNIR ===
            'hercules_1' => Attack::create([
                'name' => 'Tanngrisnir Rush',
                'description' => 'La charge du bouc de Thor.',
                'damage' => 90,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'hercules_2' => Attack::create([
                'name' => 'Titan Strength',
                'description' => 'La force titanesque des géants nordiques.',
                'damage' => 130,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'hercules_3' => Attack::create([
                'name' => 'Mjolnir Blessing',
                'description' => 'La bénédiction du marteau de Thor.',
                'damage' => 165,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'buff_attack',
                'effect_value' => 15,
            ]),

            // === FAFNER ===
            'fafner_1' => Attack::create([
                'name' => 'Dragon Scale',
                'description' => 'Les écailles du dragon Fafner repoussent les attaques.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'buff_defense',
                'effect_value' => 40,
            ]),
            'fafner_2' => Attack::create([
                'name' => 'Nidhögg Breath',
                'description' => 'Le souffle venimeux du dragon Nidhögg.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'fafner_3' => Attack::create([
                'name' => 'Ragnarok Claw',
                'description' => 'Les griffes qui annoncent le Ragnarök.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === SURT (Soul of Gold) ===
            'surt_1' => Attack::create([
                'name' => 'Flame Sword Strike',
                'description' => 'Surt frappe avec son épée enflammée.',
                'damage' => 95,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'surt_2' => Attack::create([
                'name' => 'Muspelheim Blaze',
                'description' => 'Les flammes de Muspelheim embrasent le champ de bataille.',
                'damage' => 145,
                'endurance_cost' => 50,
                'cosmos_cost' => 7,
                'effect_type' => 'burn',
                'effect_value' => 25,
            ]),
            'surt_3' => Attack::create([
                'name' => 'Ragnarok Inferno',
                'description' => 'L\'inferno final qui détruira les neuf mondes.',
                'damage' => 200,
                'endurance_cost' => 75,
                'cosmos_cost' => 10,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === SIGMUND ===
            'sigmund_1' => Attack::create([
                'name' => 'Gram Slash',
                'description' => 'Un coup de l\'épée légendaire Gram.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'sigmund_2' => Attack::create([
                'name' => 'Völsung Pride',
                'description' => 'La fierté de la lignée des Völsung.',
                'damage' => 115,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'buff_attack',
                'effect_value' => 10,
            ]),
            'sigmund_3' => Attack::create([
                'name' => 'Dragon Slayer',
                'description' => 'La technique qui tua le dragon Fafnir.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 30,
            ]),

            // === BALDER ===
            'balder_1' => Attack::create([
                'name' => 'Light of Asgard',
                'description' => 'La lumière pure du dieu Balder.',
                'damage' => 70,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'heal',
                'effect_value' => 25,
            ]),
            'balder_2' => Attack::create([
                'name' => 'Divine Protection',
                'description' => 'Balder devient quasi invulnérable.',
                'damage' => 90,
                'endurance_cost' => 35,
                'cosmos_cost' => 5,
                'effect_type' => 'buff_defense',
                'effect_value' => 50,
            ]),
            'balder_3' => Attack::create([
                'name' => 'Shining Ray',
                'description' => 'Un rayon de lumière pure qui purifie tout.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === UTGARDAR ===
            'utgardar_1' => Attack::create([
                'name' => 'Ice Prison',
                'description' => 'Une prison de glace immobilise l\'ennemi.',
                'damage' => 65,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'utgardar_2' => Attack::create([
                'name' => 'Jötunheim Frost',
                'description' => 'Le froid de Jötunheim gèle tout.',
                'damage' => 105,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 2,
            ]),
            'utgardar_3' => Attack::create([
                'name' => 'Giant\'s Wrath',
                'description' => 'La colère des géants de glace.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === ANDREAS ===
            'andreas_1' => Attack::create([
                'name' => 'Northern Gale',
                'description' => 'Un vent glacial du nord.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 1,
            ]),
            'andreas_2' => Attack::create([
                'name' => 'Frozen Spear',
                'description' => 'Une lance de glace transperce l\'ennemi.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'andreas_3' => Attack::create([
                'name' => 'Blizzard Storm',
                'description' => 'Une tempête de neige dévastatrice.',
                'damage' => 125,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === DURVAL ===
            'durval_1' => Attack::create([
                'name' => 'Battle Axe',
                'description' => 'Un coup de hache de guerre nordique.',
                'damage' => 85,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'durval_2' => Attack::create([
                'name' => 'Viking Berserker',
                'description' => 'Durval entre en rage berserk.',
                'damage' => 130,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'buff_attack',
                'effect_value' => 20,
            ]),
            'durval_3' => Attack::create([
                'name' => 'Einherjar Fury',
                'description' => 'La furie des guerriers d\'Odin.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === LOKI ===
            'loki_1' => Attack::create([
                'name' => 'Trickster Illusion',
                'description' => 'Loki crée des illusions pour confondre l\'ennemi.',
                'damage' => 60,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 2,
            ]),
            'loki_2' => Attack::create([
                'name' => 'Chaos Manipulation',
                'description' => 'Loki manipule le chaos à son avantage.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'loki_3' => Attack::create([
                'name' => 'Ragnarok Trigger',
                'description' => 'Loki déclenche les prémices du Ragnarök.',
                'damage' => 175,
                'endurance_cost' => 65,
                'cosmos_cost' => 9,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === RUNG ===
            'rung_1' => Attack::create([
                'name' => 'Runic Strike',
                'description' => 'Une attaque imprégnée de magie runique.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'rung_2' => Attack::create([
                'name' => 'Elder Futhark',
                'description' => 'Les runes anciennes s\'activent.',
                'damage' => 105,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'buff_attack',
                'effect_value' => 15,
            ]),
            'rung_3' => Attack::create([
                'name' => 'Odin\'s Rune Mastery',
                'description' => 'La maîtrise ultime des runes d\'Odin.',
                'damage' => 140,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === UR ===
            'ur_1' => Attack::create([
                'name' => 'Primal Force',
                'description' => 'La force primordiale des premiers temps.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'ur_2' => Attack::create([
                'name' => 'Ginnungagap Void',
                'description' => 'Le vide primordial qui existait avant tout.',
                'damage' => 115,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),
            'ur_3' => Attack::create([
                'name' => 'Creation Burst',
                'description' => 'L\'explosion de création des mondes.',
                'damage' => 150,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
        ];

        // ========================================
        // CRÉATION DES CARTES
        // ========================================
        
        $cards = [
            // === HILDA DE POLARIS === (Coût 10 - Technique - Boss/Déesse)
            [
                'name' => 'Hilda de Polaris',
                'faction_id' => $factionId,
                'cost' => 10,
                'health_points' => 255,
                'endurance' => 110,
                'defense' => 125,
                'power' => 75,
                'cosmos' => 10,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'light',
                'main_attack_id' => $attacks['hilda_1']->id,
                'secondary_attack_1_id' => $attacks['hilda_2']->id,
                'secondary_attack_2_id' => $attacks['hilda_3']->id,
            ],

            // === SIEGFRIED DE DUBHE === (Coût 9 - Attaquant - Leader des God Warriors)
            [
                'name' => 'Siegfried de Dubhe',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 190,
                'endurance' => 120,
                'defense' => 55,
                'power' => 110,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'ice',
                'main_attack_id' => $attacks['siegfried_1']->id,
                'secondary_attack_1_id' => $attacks['siegfried_2']->id,
                'secondary_attack_2_id' => $attacks['siegfried_3']->id,
            ],

            // === THOR DE PHECDA === (Coût 8 - Tank - Le plus résistant)
            [
                'name' => 'Thor de Phecda',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 260,
                'endurance' => 90,
                'defense' => 90,
                'power' => 40,
                'cosmos' => 8,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'thunder',
                'main_attack_id' => $attacks['thor_1']->id,
                'secondary_attack_1_id' => $attacks['thor_2']->id,
                'secondary_attack_2_id' => $attacks['thor_3']->id,
            ],

            // === FENRIR DE ALIOTH === (Coût 7 - Agile - Rapide et sauvage)
            [
                'name' => 'Fenrir de Alioth',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 150,
                'endurance' => 120,
                'defense' => 40,
                'power' => 68,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'ice',
                'main_attack_id' => $attacks['fenrir_1']->id,
                'secondary_attack_1_id' => $attacks['fenrir_2']->id,
                'secondary_attack_2_id' => $attacks['fenrir_3']->id,
            ],

            // === HAGEN DE MERAK === (Coût 7 - Bruiser - Feu et glace)
            [
                'name' => 'Hagen de Merak',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 200,
                'endurance' => 100,
                'defense' => 75,
                'power' => 65,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'fire',
                'main_attack_id' => $attacks['hagen_1']->id,
                'secondary_attack_1_id' => $attacks['hagen_2']->id,
                'secondary_attack_2_id' => $attacks['hagen_3']->id,
            ],

            // === MIME DE BENETNASCH === (Coût 7 - Technique - Contrôle)
            [
                'name' => 'Mime de Benetnasch',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 180,
                'endurance' => 80,
                'defense' => 90,
                'power' => 52,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'light',
                'main_attack_id' => $attacks['mime_1']->id,
                'secondary_attack_1_id' => $attacks['mime_2']->id,
                'secondary_attack_2_id' => $attacks['mime_3']->id,
            ],

            // === ALBERICH DE MEGREZ === (Coût 6 - Technique - Stratège fourbe)
            [
                'name' => 'Alberich de Megrez',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 160,
                'endurance' => 70,
                'defense' => 75,
                'power' => 45,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'earth',
                'main_attack_id' => $attacks['alberich_1']->id,
                'secondary_attack_1_id' => $attacks['alberich_2']->id,
                'secondary_attack_2_id' => $attacks['alberich_3']->id,
            ],

            // === SHIDO DE MIZAR === (Coût 7 - Équilibré - Jumeau de lumière)
            [
                'name' => 'Shido de Mizar',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 180,
                'endurance' => 100,
                'defense' => 70,
                'power' => 68,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'ice',
                'main_attack_id' => $attacks['shido_1']->id,
                'secondary_attack_1_id' => $attacks['shido_2']->id,
                'secondary_attack_2_id' => $attacks['shido_3']->id,
            ],

            // === BADO D'ALCOR === (Coût 7 - Attaquant - Jumeau des ombres)
            [
                'name' => 'Bado d\'Alcor',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 150,
                'endurance' => 100,
                'defense' => 45,
                'power' => 85,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['bado_1']->id,
                'secondary_attack_1_id' => $attacks['bado_2']->id,
                'secondary_attack_2_id' => $attacks['bado_3']->id,
            ],

            // === FRODI === (Coût 5 - Équilibré - Soul of Gold)
            [
                'name' => 'Frodi',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 135,
                'endurance' => 80,
                'defense' => 50,
                'power' => 48,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'ice',
                'main_attack_id' => $attacks['frodi_1']->id,
                'secondary_attack_1_id' => $attacks['frodi_2']->id,
                'secondary_attack_2_id' => $attacks['frodi_3']->id,
            ],

            // === HERCULES DE TANNGRISNIR === (Coût 6 - Bruiser)
            [
                'name' => 'Hércules de Tanngrisnir',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 175,
                'endurance' => 90,
                'defense' => 65,
                'power' => 55,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'thunder',
                'main_attack_id' => $attacks['hercules_1']->id,
                'secondary_attack_1_id' => $attacks['hercules_2']->id,
                'secondary_attack_2_id' => $attacks['hercules_3']->id,
            ],

            // === FAFNER === (Coût 6 - Tank - Dragon)
            [
                'name' => 'Fafner',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 200,
                'endurance' => 70,
                'defense' => 70,
                'power' => 30,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['fafner_1']->id,
                'secondary_attack_1_id' => $attacks['fafner_2']->id,
                'secondary_attack_2_id' => $attacks['fafner_3']->id,
            ],

            // === SURT === (Coût 9 - Attaquant - Géant de feu)
            [
                'name' => 'Surt',
                'faction_id' => $factionId,
                'cost' => 9,
                'health_points' => 190,
                'endurance' => 120,
                'defense' => 55,
                'power' => 110,
                'cosmos' => 9,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'fire',
                'main_attack_id' => $attacks['surt_1']->id,
                'secondary_attack_1_id' => $attacks['surt_2']->id,
                'secondary_attack_2_id' => $attacks['surt_3']->id,
            ],

            // === SIGMUND === (Coût 6 - Attaquant - Héros légendaire)
            [
                'name' => 'Sigmund',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 130,
                'endurance' => 90,
                'defense' => 40,
                'power' => 70,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'light',
                'main_attack_id' => $attacks['sigmund_1']->id,
                'secondary_attack_1_id' => $attacks['sigmund_2']->id,
                'secondary_attack_2_id' => $attacks['sigmund_3']->id,
            ],

            // === BALDER === (Coût 7 - Technique - Dieu de la lumière)
            [
                'name' => 'Balder',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 180,
                'endurance' => 80,
                'defense' => 90,
                'power' => 52,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'divine',
                'element' => 'light',
                'main_attack_id' => $attacks['balder_1']->id,
                'secondary_attack_1_id' => $attacks['balder_2']->id,
                'secondary_attack_2_id' => $attacks['balder_3']->id,
            ],

            // === UTGARDAR === (Coût 6 - Technique - Géant de glace)
            [
                'name' => 'Utgardar',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 160,
                'endurance' => 70,
                'defense' => 75,
                'power' => 45,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'ice',
                'main_attack_id' => $attacks['utgardar_1']->id,
                'secondary_attack_1_id' => $attacks['utgardar_2']->id,
                'secondary_attack_2_id' => $attacks['utgardar_3']->id,
            ],

            // === ANDREAS === (Coût 4 - Équilibré - Guerrier nordique)
            [
                'name' => 'Andreas',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 110,
                'endurance' => 65,
                'defense' => 40,
                'power' => 38,
                'cosmos' => 4,
                'rarity' => 'common',
                'armor_type' => 'silver',
                'element' => 'ice',
                'main_attack_id' => $attacks['andreas_1']->id,
                'secondary_attack_1_id' => $attacks['andreas_2']->id,
                'secondary_attack_2_id' => $attacks['andreas_3']->id,
            ],

            // === DURVAL === (Coût 5 - Bruiser - Viking)
            [
                'name' => 'Durval',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 150,
                'endurance' => 80,
                'defense' => 55,
                'power' => 45,
                'cosmos' => 5,
                'rarity' => 'common',
                'armor_type' => 'silver',
                'element' => 'earth',
                'main_attack_id' => $attacks['durval_1']->id,
                'secondary_attack_1_id' => $attacks['durval_2']->id,
                'secondary_attack_2_id' => $attacks['durval_3']->id,
            ],

            // === LOKI === (Coût 8 - Technique - Dieu du chaos)
            [
                'name' => 'Loki',
                'faction_id' => $factionId,
                'cost' => 8,
                'health_points' => 205,
                'endurance' => 90,
                'defense' => 100,
                'power' => 60,
                'cosmos' => 8,
                'rarity' => 'legendary',
                'armor_type' => 'divine',
                'element' => 'darkness',
                'main_attack_id' => $attacks['loki_1']->id,
                'secondary_attack_1_id' => $attacks['loki_2']->id,
                'secondary_attack_2_id' => $attacks['loki_3']->id,
            ],

            // === RUNG === (Coût 5 - Technique - Maître des runes)
            [
                'name' => 'Rung',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 135,
                'endurance' => 60,
                'defense' => 65,
                'power' => 38,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['rung_1']->id,
                'secondary_attack_1_id' => $attacks['rung_2']->id,
                'secondary_attack_2_id' => $attacks['rung_3']->id,
            ],

            // === UR === (Coût 6 - Équilibré - Force primordiale)
            [
                'name' => 'Ur',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 160,
                'endurance' => 90,
                'defense' => 60,
                'power' => 58,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'divine',
                'element' => 'earth',
                'main_attack_id' => $attacks['ur_1']->id,
                'secondary_attack_1_id' => $attacks['ur_2']->id,
                'secondary_attack_2_id' => $attacks['ur_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 21 Guerriers d\'Asgard créés avec succès !');
        $this->command->info('✅ 63 Attaques créées avec succès !');
        $this->command->info('🐺 Faction: Guerriers Divins d\'Asgard');
    }
}
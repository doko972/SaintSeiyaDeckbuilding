<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Attack;
use App\Models\Faction;

class SilverSaintsSeeder extends Seeder
{
    /**
     * Seeder pour les Chevaliers d'Argent
     * 20 Chevaliers d'Argent
     *
     * Système d'équilibrage :
     * - Budget = Coût × 20
     * - Cosmos attaque max = 10
     * - 6 archétypes : Tank, Bruiser, Attaquant, Équilibré, Agile, Technique
     */
    public function run(): void
    {
        // Récupérer ou créer la faction Chevaliers d'Argent
        $faction = Faction::firstOrCreate(
            ['name' => 'Chevaliers d\'Argent'],
            [
                'description' => 'Les Chevaliers d\'Argent sont les guerriers de rang intermédiaire au service d\'Athéna. Plus puissants que les Chevaliers de Bronze, ils exécutent les missions les plus importantes du Sanctuaire.',
                'color_primary' => '#C0C0C0',   // Argent
                'color_secondary' => '#6B7280', // Gris
            ]
        );

        $factionId = $faction->id;

        // ========================================
        // CRÉATION DES ATTAQUES
        // ========================================

        $attacks = [
            // === ÁGORA DU LOTUS ===
            'agora_1' => Attack::create([
                'name' => 'Lotus Petal',
                'description' => 'Des pétales de lotus tranchants volent vers l\'ennemi.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'agora_2' => Attack::create([
                'name' => 'Blooming Lotus',
                'description' => 'Le lotus s\'épanouit en une explosion d\'énergie.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'heal',
                'effect_value' => 15,
            ]),
            'agora_3' => Attack::create([
                'name' => 'Lotus Garden',
                'description' => 'Un jardin de lotus mortels entoure l\'adversaire.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === ALBIORE DE CÉPHÉE ===
            'albiore_1' => Attack::create([
                'name' => 'Cepheus Chain',
                'description' => 'Les chaînes de Céphée immobilisent l\'ennemi.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'albiore_2' => Attack::create([
                'name' => 'Andromeda\'s Teacher',
                'description' => 'Albiore utilise les techniques qu\'il a enseignées.',
                'damage' => 115,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'albiore_3' => Attack::create([
                'name' => 'King\'s Judgement',
                'description' => 'Le jugement du roi Céphée s\'abat sur les traîtres.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === ALGETHI D'HÉRACLÈS ===
            'algethi_1' => Attack::create([
                'name' => 'Heracles Punch',
                'description' => 'Un coup de poing digne du héros Héraclès.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'algethi_2' => Attack::create([
                'name' => 'Kornephoros',
                'description' => 'La technique du porteur de massue frappe.',
                'damage' => 125,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'algethi_3' => Attack::create([
                'name' => 'Twelve Labors',
                'description' => 'La force des douze travaux d\'Héraclès.',
                'damage' => 160,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'buff_attack',
                'effect_value' => 20,
            ]),

            // === ALGOL DE PERSÉE ===
            'algol_1' => Attack::create([
                'name' => 'Medusa Shield',
                'description' => 'Le bouclier de Méduse commence à pétrifier.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'debuff',
                'effect_value' => 10,
            ]),
            'algol_2' => Attack::create([
                'name' => 'Demon\'s Gorgon',
                'description' => 'Le regard de la Gorgone pétrifie partiellement.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'algol_3' => Attack::create([
                'name' => 'Ra\'s Al Ghul Gorgonio',
                'description' => 'La tête du démon transforme l\'ennemi en pierre.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === ARACNE DE LA TARENTULE ===
            'aracne_1' => Attack::create([
                'name' => 'Spider Web',
                'description' => 'Une toile d\'araignée piège l\'adversaire.',
                'damage' => 60,
                'endurance_cost' => 15,
                'cosmos_cost' => 2,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'aracne_2' => Attack::create([
                'name' => 'Tarantula Net',
                'description' => 'Le filet de la tarentule immobilise complètement.',
                'damage' => 95,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'aracne_3' => Attack::create([
                'name' => 'Venomous Bite',
                'description' => 'La morsure venimeuse de la tarentule.',
                'damage' => 130,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),

            // === ASTERION DES CHIENS DE CHASSE ===
            'asterion_1' => Attack::create([
                'name' => 'Million Ghost Attack',
                'description' => 'Asterion lit l\'esprit de son adversaire.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'asterion_2' => Attack::create([
                'name' => 'Hunting Dogs Assault',
                'description' => 'L\'assaut des chiens de chasse.',
                'damage' => 110,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'asterion_3' => Attack::create([
                'name' => 'Mind Reading Strike',
                'description' => 'Asterion anticipe et frappe avec précision.',
                'damage' => 145,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ]),

            // === BABEL DU CENTAURE ===
            'babel_1' => Attack::create([
                'name' => 'Centaur Kick',
                'description' => 'Un coup de sabot puissant du centaure.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'babel_2' => Attack::create([
                'name' => 'Flame Tongue',
                'description' => 'Babel crache des flammes brûlantes.',
                'damage' => 115,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ]),
            'babel_3' => Attack::create([
                'name' => 'Photon Burst',
                'description' => 'Une explosion de photons dévaste l\'ennemi.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ]),

            // === CAPELLA DU COCHER (Auriga) ===
            'capella_1' => Attack::create([
                'name' => 'Saucer Attack',
                'description' => 'Capella lance ses disques tranchants.',
                'damage' => 75,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'capella_2' => Attack::create([
                'name' => 'Disc Boomerang',
                'description' => 'Les disques reviennent après avoir frappé.',
                'damage' => 110,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'capella_3' => Attack::create([
                'name' => 'Charioteer\'s Fury',
                'description' => 'La fureur du cocher multiplie les disques.',
                'damage' => 145,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === DANTE DE CERBÈRE ===
            'dante_1' => Attack::create([
                'name' => 'Cerberus Fang',
                'description' => 'Les crocs de Cerbère mordent.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ]),
            'dante_2' => Attack::create([
                'name' => 'Three-Headed Attack',
                'description' => 'Les trois têtes de Cerbère attaquent simultanément.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'dante_3' => Attack::create([
                'name' => 'Guardian of Hades',
                'description' => 'La fureur du gardien des Enfers.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'burn',
                'effect_value' => 25,
            ]),

            // === DIO DE LA MOUCHE (Musca) ===
            'dio_1' => Attack::create([
                'name' => 'Fly Attack',
                'description' => 'Une attaque rapide comme une mouche.',
                'damage' => 65,
                'endurance_cost' => 15,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'dio_2' => Attack::create([
                'name' => 'Dead Insect Swarm',
                'description' => 'Une nuée d\'insectes mortels envahit l\'ennemi.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 12,
            ]),
            'dio_3' => Attack::create([
                'name' => 'Musca\'s Plague',
                'description' => 'La peste de la mouche se répand.',
                'damage' => 135,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),

            // === JAMIAN DU CORBEAU ===
            'jamian_1' => Attack::create([
                'name' => 'Black Wing Shaft',
                'description' => 'Des plumes noires tranchantes volent.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'jamian_2' => Attack::create([
                'name' => 'Crow Flock',
                'description' => 'Une nuée de corbeaux attaque l\'ennemi.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'jamian_3' => Attack::create([
                'name' => 'Corvus Dark Storm',
                'description' => 'La tempête sombre du corbeau.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === MARIN DE L'AIGLE ===
            'marin_1' => Attack::create([
                'name' => 'Eagle Toe Flash',
                'description' => 'Un coup de pied fulgurant comme l\'aigle.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'marin_2' => Attack::create([
                'name' => 'Ryusei Ken Training',
                'description' => 'Marin exécute la technique qu\'elle a enseignée à Seiya.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'marin_3' => Attack::create([
                'name' => 'Eagle\'s Dive',
                'description' => 'Marin plonge du ciel comme un aigle sur sa proie.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === MISTY DU LÉZARD ===
            'misty_1' => Attack::create([
                'name' => 'Marble Tripper',
                'description' => 'Misty glisse comme un lézard et frappe.',
                'damage' => 75,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'misty_2' => Attack::create([
                'name' => 'Lizard Tail',
                'description' => 'La queue du lézard fouette l\'adversaire.',
                'damage' => 110,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 10,
            ]),
            'misty_3' => Attack::create([
                'name' => 'Narcissist\'s Beauty',
                'description' => 'Misty éblouit l\'ennemi par sa beauté légendaire.',
                'damage' => 145,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),

            // === MOUSES DE LA BALEINE ===
            'mouses_1' => Attack::create([
                'name' => 'Whale Crash',
                'description' => 'Mouses écrase comme une baleine.',
                'damage' => 85,
                'endurance_cost' => 30,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'mouses_2' => Attack::create([
                'name' => 'Deep Sea Pressure',
                'description' => 'La pression des profondeurs écrase l\'ennemi.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'mouses_3' => Attack::create([
                'name' => 'Cetus Tidal Wave',
                'description' => 'Le raz-de-marée de la baleine engloutit tout.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === ORPHÉE DE LA LYRE ===
            'orphee_1' => Attack::create([
                'name' => 'String Nocturne',
                'description' => 'Une mélodie nocturne apaisante mais mortelle.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'orphee_2' => Attack::create([
                'name' => 'Death Trip Serenade',
                'description' => 'La sérénade qui guide vers la mort.',
                'damage' => 125,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'orphee_3' => Attack::create([
                'name' => 'Stringer Fine',
                'description' => 'Les cordes de la lyre vibrent en une attaque ultime.',
                'damage' => 165,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ]),

            // === SHAINA DU SERPENTAIRE (Ophiuchus) ===
            'shaina_1' => Attack::create([
                'name' => 'Thunder Claw',
                'description' => 'Les griffes de tonnerre de Shaina frappent.',
                'damage' => 80,
                'endurance_cost' => 25,
                'cosmos_cost' => 3,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'shaina_2' => Attack::create([
                'name' => 'Thunder Cobra',
                'description' => 'Le cobra de tonnerre mord avec la foudre.',
                'damage' => 120,
                'endurance_cost' => 45,
                'cosmos_cost' => 6,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ]),
            'shaina_3' => Attack::create([
                'name' => 'Ophiuchus Venom',
                'description' => 'Le venin du serpentaire paralyse et détruit.',
                'damage' => 155,
                'endurance_cost' => 60,
                'cosmos_cost' => 8,
                'effect_type' => 'burn',
                'effect_value' => 25,
            ]),

            // === SHIVA DU PAON ===
            'shiva_1' => Attack::create([
                'name' => 'Peacock Feather',
                'description' => 'Des plumes de paon tranchantes volent.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'shiva_2' => Attack::create([
                'name' => 'Senju Shinken',
                'description' => 'Les mille bras divins frappent.',
                'damage' => 115,
                'endurance_cost' => 40,
                'cosmos_cost' => 5,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'shiva_3' => Attack::create([
                'name' => 'Pavo\'s Dance',
                'description' => 'La danse du paon hypnotise et détruit.',
                'damage' => 150,
                'endurance_cost' => 55,
                'cosmos_cost' => 7,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ]),

            // === SIRIUS DU GRAND CHIEN ===
            'sirius_1' => Attack::create([
                'name' => 'Dog Star Flash',
                'description' => 'L\'éclat de l\'étoile du chien aveugle.',
                'damage' => 75,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'sirius_2' => Attack::create([
                'name' => 'Great Dog Fang',
                'description' => 'Les crocs du grand chien mordent profondément.',
                'damage' => 110,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'burn',
                'effect_value' => 12,
            ]),
            'sirius_3' => Attack::create([
                'name' => 'Canis Major Assault',
                'description' => 'L\'assaut du Grand Chien dans toute sa puissance.',
                'damage' => 145,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === SPARTAN DE LA FLÈCHE ===
            'spartan_1' => Attack::create([
                'name' => 'Arrow Shot',
                'description' => 'Une flèche précise file vers l\'ennemi.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'spartan_2' => Attack::create([
                'name' => 'Phantom Arrow',
                'description' => 'Une flèche fantôme traverse les défenses.',
                'damage' => 105,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'spartan_3' => Attack::create([
                'name' => 'Sagitta Rain',
                'description' => 'Une pluie de flèches s\'abat sur l\'adversaire.',
                'damage' => 140,
                'endurance_cost' => 50,
                'cosmos_cost' => 6,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),

            // === TREMY DE LA FLÈCHE ===
            'tremy_1' => Attack::create([
                'name' => 'Swift Arrow',
                'description' => 'Une flèche rapide comme l\'éclair.',
                'damage' => 65,
                'endurance_cost' => 15,
                'cosmos_cost' => 2,
                'effect_type' => 'none',
                'effect_value' => 0,
            ]),
            'tremy_2' => Attack::create([
                'name' => 'Piercing Shot',
                'description' => 'Un tir perçant qui traverse l\'armure.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 4,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ]),
            'tremy_3' => Attack::create([
                'name' => 'Arrow Storm',
                'description' => 'Une tempête de flèches dévastatrice.',
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
            // === ÁGORA DU LOTUS === (Coût 5 - Technique)
            [
                'name' => 'Ágora du Lotus',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 130,
                'endurance' => 70,
                'defense' => 55,
                'power' => 45,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'earth',
                'main_attack_id' => $attacks['agora_1']->id,
                'secondary_attack_1_id' => $attacks['agora_2']->id,
                'secondary_attack_2_id' => $attacks['agora_3']->id,
            ],

            // === ALBIORE DE CÉPHÉE === (Coût 6 - Technique)
            [
                'name' => 'Albiore de Céphée',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 155,
                'endurance' => 80,
                'defense' => 65,
                'power' => 50,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['albiore_1']->id,
                'secondary_attack_1_id' => $attacks['albiore_2']->id,
                'secondary_attack_2_id' => $attacks['albiore_3']->id,
            ],

            // === ALGETHI D'HÉRACLÈS === (Coût 7 - Bruiser)
            [
                'name' => 'Algethi d\'Héraclès',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 185,
                'endurance' => 95,
                'defense' => 70,
                'power' => 58,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'earth',
                'main_attack_id' => $attacks['algethi_1']->id,
                'secondary_attack_1_id' => $attacks['algethi_2']->id,
                'secondary_attack_2_id' => $attacks['algethi_3']->id,
            ],

            // === ALGOL DE PERSÉE === (Coût 6 - Technique)
            [
                'name' => 'Algol de Persée',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 150,
                'endurance' => 75,
                'defense' => 65,
                'power' => 52,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['algol_1']->id,
                'secondary_attack_1_id' => $attacks['algol_2']->id,
                'secondary_attack_2_id' => $attacks['algol_3']->id,
            ],

            // === ARACNE DE LA TARENTULE === (Coût 5 - Technique)
            [
                'name' => 'Aracne de la Tarentule',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 125,
                'endurance' => 70,
                'defense' => 50,
                'power' => 48,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['aracne_1']->id,
                'secondary_attack_1_id' => $attacks['aracne_2']->id,
                'secondary_attack_2_id' => $attacks['aracne_3']->id,
            ],

            // === ASTERION DES CHIENS DE CHASSE === (Coût 6 - Technique)
            [
                'name' => 'Asterion des Chiens de Chasse',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 150,
                'endurance' => 80,
                'defense' => 60,
                'power' => 55,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['asterion_1']->id,
                'secondary_attack_1_id' => $attacks['asterion_2']->id,
                'secondary_attack_2_id' => $attacks['asterion_3']->id,
            ],

            // === BABEL DU CENTAURE === (Coût 6 - Attaquant)
            [
                'name' => 'Babel du Centaure',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 145,
                'endurance' => 90,
                'defense' => 50,
                'power' => 60,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'fire',
                'main_attack_id' => $attacks['babel_1']->id,
                'secondary_attack_1_id' => $attacks['babel_2']->id,
                'secondary_attack_2_id' => $attacks['babel_3']->id,
            ],

            // === CAPELLA DU COCHER === (Coût 5 - Agile)
            [
                'name' => 'Capella du Cocher',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 120,
                'endurance' => 85,
                'defense' => 40,
                'power' => 52,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['capella_1']->id,
                'secondary_attack_1_id' => $attacks['capella_2']->id,
                'secondary_attack_2_id' => $attacks['capella_3']->id,
            ],

            // === DANTE DE CERBÈRE === (Coût 6 - Bruiser)
            [
                'name' => 'Dante de Cerbère',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 165,
                'endurance' => 85,
                'defense' => 60,
                'power' => 55,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'fire',
                'main_attack_id' => $attacks['dante_1']->id,
                'secondary_attack_1_id' => $attacks['dante_2']->id,
                'secondary_attack_2_id' => $attacks['dante_3']->id,
            ],

            // === DIO DE LA MOUCHE === (Coût 4 - Agile)
            [
                'name' => 'Dio de la Mouche',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 100,
                'endurance' => 75,
                'defense' => 35,
                'power' => 45,
                'cosmos' => 4,
                'rarity' => 'common',
                'armor_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['dio_1']->id,
                'secondary_attack_1_id' => $attacks['dio_2']->id,
                'secondary_attack_2_id' => $attacks['dio_3']->id,
            ],

            // === JAMIAN DU CORBEAU === (Coût 5 - Équilibré)
            [
                'name' => 'Jamian du Corbeau',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 135,
                'endurance' => 80,
                'defense' => 50,
                'power' => 50,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'darkness',
                'main_attack_id' => $attacks['jamian_1']->id,
                'secondary_attack_1_id' => $attacks['jamian_2']->id,
                'secondary_attack_2_id' => $attacks['jamian_3']->id,
            ],

            // === MARIN DE L'AIGLE === (Coût 7 - Attaquant)
            [
                'name' => 'Marin de l\'Aigle',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 165,
                'endurance' => 100,
                'defense' => 55,
                'power' => 70,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['marin_1']->id,
                'secondary_attack_1_id' => $attacks['marin_2']->id,
                'secondary_attack_2_id' => $attacks['marin_3']->id,
            ],

            // === MISTY DU LÉZARD === (Coût 6 - Équilibré)
            [
                'name' => 'Misty du Lézard',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 155,
                'endurance' => 85,
                'defense' => 55,
                'power' => 55,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'earth',
                'main_attack_id' => $attacks['misty_1']->id,
                'secondary_attack_1_id' => $attacks['misty_2']->id,
                'secondary_attack_2_id' => $attacks['misty_3']->id,
            ],

            // === MOUSES DE LA BALEINE === (Coût 6 - Tank)
            [
                'name' => 'Mouses de la Baleine',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 180,
                'endurance' => 75,
                'defense' => 70,
                'power' => 42,
                'cosmos' => 6,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'water',
                'main_attack_id' => $attacks['mouses_1']->id,
                'secondary_attack_1_id' => $attacks['mouses_2']->id,
                'secondary_attack_2_id' => $attacks['mouses_3']->id,
            ],

            // === ORPHÉE DE LA LYRE === (Coût 7 - Technique)
            [
                'name' => 'Orphée de la Lyre',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 170,
                'endurance' => 85,
                'defense' => 70,
                'power' => 60,
                'cosmos' => 7,
                'rarity' => 'legendary',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['orphee_1']->id,
                'secondary_attack_1_id' => $attacks['orphee_2']->id,
                'secondary_attack_2_id' => $attacks['orphee_3']->id,
            ],

            // === SHAINA DU SERPENTAIRE === (Coût 7 - Attaquant)
            [
                'name' => 'Shaina du Serpentaire',
                'faction_id' => $factionId,
                'cost' => 7,
                'health_points' => 165,
                'endurance' => 100,
                'defense' => 55,
                'power' => 68,
                'cosmos' => 7,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'thunder',
                'main_attack_id' => $attacks['shaina_1']->id,
                'secondary_attack_1_id' => $attacks['shaina_2']->id,
                'secondary_attack_2_id' => $attacks['shaina_3']->id,
            ],

            // === SHIVA DU PAON === (Coût 6 - Équilibré)
            [
                'name' => 'Shiva du Paon',
                'faction_id' => $factionId,
                'cost' => 6,
                'health_points' => 155,
                'endurance' => 85,
                'defense' => 55,
                'power' => 55,
                'cosmos' => 6,
                'rarity' => 'epic',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['shiva_1']->id,
                'secondary_attack_1_id' => $attacks['shiva_2']->id,
                'secondary_attack_2_id' => $attacks['shiva_3']->id,
            ],

            // === SIRIUS DU GRAND CHIEN === (Coût 5 - Équilibré)
            [
                'name' => 'Sirius du Grand Chien',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 140,
                'endurance' => 80,
                'defense' => 50,
                'power' => 50,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'fire',
                'main_attack_id' => $attacks['sirius_1']->id,
                'secondary_attack_1_id' => $attacks['sirius_2']->id,
                'secondary_attack_2_id' => $attacks['sirius_3']->id,
            ],

            // === SPARTAN DE LA FLÈCHE === (Coût 5 - Agile)
            [
                'name' => 'Spartan de la Flèche',
                'faction_id' => $factionId,
                'cost' => 5,
                'health_points' => 125,
                'endurance' => 85,
                'defense' => 40,
                'power' => 52,
                'cosmos' => 5,
                'rarity' => 'rare',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['spartan_1']->id,
                'secondary_attack_1_id' => $attacks['spartan_2']->id,
                'secondary_attack_2_id' => $attacks['spartan_3']->id,
            ],

            // === TREMY DE LA FLÈCHE === (Coût 4 - Agile)
            [
                'name' => 'Tremy de la Flèche',
                'faction_id' => $factionId,
                'cost' => 4,
                'health_points' => 105,
                'endurance' => 75,
                'defense' => 35,
                'power' => 45,
                'cosmos' => 4,
                'rarity' => 'common',
                'armor_type' => 'silver',
                'element' => 'light',
                'main_attack_id' => $attacks['tremy_1']->id,
                'secondary_attack_1_id' => $attacks['tremy_2']->id,
                'secondary_attack_2_id' => $attacks['tremy_3']->id,
            ],
        ];

        // Créer toutes les cartes
        foreach ($cards as $cardData) {
            Card::create($cardData);
        }

        $this->command->info('✅ 20 Chevaliers d\'Argent créés avec succès !');
        $this->command->info('✅ 60 Attaques créées avec succès !');
        $this->command->info('🥈 Les Chevaliers d\'Argent sont prêts au combat !');
    }
}

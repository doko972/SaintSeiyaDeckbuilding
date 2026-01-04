<?php

namespace Database\Seeders;

use App\Models\Attack;
use Illuminate\Database\Seeder;

class AttackSeeder extends Seeder
{
    public function run(): void
    {
        $attacks = [
            // ========================================
            // ATTAQUES CHEVALIERS DE BRONZE
            // ========================================
            
            // Seiya de Pégase
            [
                'name' => 'Pegasus Ryusei Ken',
                'description' => 'Les météores de Pégase ! Une pluie de coups à la vitesse de la lumière.',
                'damage' => 80,
                'endurance_cost' => 20,
                'cosmos_cost' => 15,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Pegasus Suisei Ken',
                'description' => 'Le poing de la comète de Pégase, une attaque concentrée dévastatrice.',
                'damage' => 120,
                'endurance_cost' => 35,
                'cosmos_cost' => 25,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ],
            [
                'name' => 'Pegasus Rolling Crash',
                'description' => 'Seiya saisit son ennemi et le projette violemment au sol.',
                'damage' => 100,
                'endurance_cost' => 30,
                'cosmos_cost' => 20,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ],

            // Hyōga du Cygne
            [
                'name' => 'Diamond Dust',
                'description' => 'Poussière de Diamant ! Une attaque glaciale qui gèle tout sur son passage.',
                'damage' => 75,
                'endurance_cost' => 25,
                'cosmos_cost' => 20,
                'effect_type' => 'freeze',
                'effect_value' => 2,
            ],
            [
                'name' => 'Aurora Thunder Attack',
                'description' => 'L\'attaque du tonnerre de l\'Aurore, combinant glace et foudre.',
                'damage' => 100,
                'endurance_cost' => 30,
                'cosmos_cost' => 30,
                'effect_type' => 'freeze',
                'effect_value' => 1,
            ],
            [
                'name' => 'Kholodnyi Smerch',
                'description' => 'La Tornade Glaciale, technique secrète transmise par Camus.',
                'damage' => 140,
                'endurance_cost' => 40,
                'cosmos_cost' => 35,
                'effect_type' => 'freeze',
                'effect_value' => 2,
            ],

            // Shiryū du Dragon
            [
                'name' => 'Rozan Sho Ryu Ha',
                'description' => 'Le Dragon Ascendant ! La puissance du dragon de Rozan.',
                'damage' => 90,
                'endurance_cost' => 25,
                'cosmos_cost' => 20,
                'effect_type' => 'buff_attack',
                'effect_value' => 10,
            ],
            [
                'name' => 'Rozan Hyaku Ryu Ha',
                'description' => 'Les Cent Dragons de Rozan ! Une attaque ultime libérant la puissance maximale.',
                'damage' => 150,
                'endurance_cost' => 50,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Rozan Ko Ryu Ha',
                'description' => 'Le Dragon Furieux, technique sacrificielle de Dohko.',
                'damage' => 180,
                'endurance_cost' => 60,
                'cosmos_cost' => 50,
                'effect_type' => 'drain',
                'effect_value' => 20,
            ],

            // Shun d'Andromède
            [
                'name' => 'Nebula Chain',
                'description' => 'La Chaîne Nébulaire peut attaquer et défendre simultanément.',
                'damage' => 70,
                'endurance_cost' => 20,
                'cosmos_cost' => 15,
                'effect_type' => 'buff_defense',
                'effect_value' => 15,
            ],
            [
                'name' => 'Nebula Storm',
                'description' => 'La Tempête Nébulaire, déchaînant la puissance cosmique de la chaîne.',
                'damage' => 130,
                'endurance_cost' => 40,
                'cosmos_cost' => 35,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ],
            [
                'name' => 'Thunder Wave',
                'description' => 'La Vague de Tonnerre, une déferlante d\'énergie via la chaîne.',
                'damage' => 85,
                'endurance_cost' => 25,
                'cosmos_cost' => 20,
                'effect_type' => 'stun',
                'effect_value' => 1,
            ],

            // Ikki du Phénix
            [
                'name' => 'Phoenix Genma Ken',
                'description' => 'L\'Illusion Démoniaque du Phénix détruit l\'esprit de l\'adversaire.',
                'damage' => 60,
                'endurance_cost' => 30,
                'cosmos_cost' => 35,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ],
            [
                'name' => 'Ho Yoku Tensho',
                'description' => 'Les Ailes Ardentes du Phénix ! Une explosion de flammes dévastatrice.',
                'damage' => 160,
                'endurance_cost' => 45,
                'cosmos_cost' => 40,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ],
            [
                'name' => 'Thunder Claw',
                'description' => 'Les griffes foudroyantes du Phénix.',
                'damage' => 85,
                'endurance_cost' => 20,
                'cosmos_cost' => 20,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ],

            // ========================================
            // ATTAQUES CHEVALIERS D'OR
            // ========================================

            // Mū du Bélier
            [
                'name' => 'Stardust Revolution',
                'description' => 'La Révolution Stellaire, une pluie d\'étoiles dévastatrice.',
                'damage' => 170,
                'endurance_cost' => 40,
                'cosmos_cost' => 40,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Crystal Wall',
                'description' => 'Le Mur de Cristal, une barrière protectrice impénétrable.',
                'damage' => 0,
                'endurance_cost' => 30,
                'cosmos_cost' => 25,
                'effect_type' => 'buff_defense',
                'effect_value' => 50,
            ],
            [
                'name' => 'Starlight Extinction',
                'description' => 'L\'Extinction Stellaire, téléporte l\'ennemi dans une autre dimension.',
                'damage' => 200,
                'endurance_cost' => 55,
                'cosmos_cost' => 55,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],

            // Aldébaran du Taureau
            [
                'name' => 'Great Horn',
                'description' => 'La Grande Corne ! Une attaque frontale d\'une puissance dévastatrice.',
                'damage' => 180,
                'endurance_cost' => 35,
                'cosmos_cost' => 30,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],
            [
                'name' => 'Titan\'s Nova',
                'description' => 'La Nova du Titan, concentrant toute la force d\'Aldébaran.',
                'damage' => 220,
                'endurance_cost' => 50,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],

            // Saga des Gémeaux
            [
                'name' => 'Galaxian Explosion',
                'description' => 'L\'Explosion Galactique ! L\'attaque ultime des Gémeaux.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Another Dimension',
                'description' => 'Une Autre Dimension ! Envoie l\'ennemi dans un espace parallèle.',
                'damage' => 150,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ],
            [
                'name' => 'Demon Emperor Fist',
                'description' => 'Le Poing de l\'Empereur Démoniaque, technique du Saga maléfique.',
                'damage' => 140,
                'endurance_cost' => 40,
                'cosmos_cost' => 40,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ],

            // Death Mask du Cancer
            [
                'name' => 'Sekishiki Meikai Ha',
                'description' => 'Les Vagues de l\'Enfer ! Envoie l\'âme de l\'ennemi au Yomotsu.',
                'damage' => 160,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'drain',
                'effect_value' => 25,
            ],
            [
                'name' => 'Praesepe Underworld Waves',
                'description' => 'Les Vagues Infernales de Praesepe, invoquant les âmes des morts.',
                'damage' => 180,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ],

            // Aiolia du Lion
            [
                'name' => 'Lightning Bolt',
                'description' => 'L\'éclair du Lion, une attaque à la vitesse de la lumière.',
                'damage' => 130,
                'endurance_cost' => 30,
                'cosmos_cost' => 25,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Lightning Plasma',
                'description' => 'Plasma Foudroyant ! Des milliards de volts concentrés.',
                'damage' => 180,
                'endurance_cost' => 45,
                'cosmos_cost' => 40,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],
            [
                'name' => 'Photon Burst',
                'description' => 'L\'Éclat de Photons, libérant l\'énergie pure du Lion.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ],

            // Shaka de la Vierge
            [
                'name' => 'Tenbu Horin',
                'description' => 'Le Trésor du Ciel ! Retire les 5 sens de l\'adversaire un par un.',
                'damage' => 190,
                'endurance_cost' => 50,
                'cosmos_cost' => 55,
                'effect_type' => 'debuff',
                'effect_value' => 40,
            ],
            [
                'name' => 'Tenma Kofuku',
                'description' => 'La Soumission Démoniaque, forçant l\'ennemi à se soumettre.',
                'damage' => 160,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'stun',
                'effect_value' => 3,
            ],
            [
                'name' => 'Rikudo Rinne',
                'description' => 'Les Six Mondes de la Réincarnation, technique interdite suprême.',
                'damage' => 250,
                'endurance_cost' => 70,
                'cosmos_cost' => 70,
                'effect_type' => 'drain',
                'effect_value' => 40,
            ],

            // Dohko de la Balance
            [
                'name' => 'Rozan Hyaku Ryu Ha Master',
                'description' => 'Les Cent Dragons de Rozan ! L\'attaque ultime du vieux maître.',
                'damage' => 190,
                'endurance_cost' => 50,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Rozan Ko Ryu Ha Master',
                'description' => 'Le Dragon Furieux, technique sacrificielle millénaire.',
                'damage' => 220,
                'endurance_cost' => 60,
                'cosmos_cost' => 55,
                'effect_type' => 'drain',
                'effect_value' => 30,
            ],

            // Milo du Scorpion
            [
                'name' => 'Scarlet Needle',
                'description' => 'L\'Aiguille Écarlate du Scorpion, 15 piqûres mortelles.',
                'damage' => 95,
                'endurance_cost' => 25,
                'cosmos_cost' => 30,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ],
            [
                'name' => 'Scarlet Needle Antares',
                'description' => 'Antarès ! La 15ème et ultime piqûre, coup de grâce mortel.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'burn',
                'effect_value' => 30,
            ],
            [
                'name' => 'Restriction',
                'description' => 'Restriction, paralysant les mouvements de l\'adversaire.',
                'damage' => 50,
                'endurance_cost' => 30,
                'cosmos_cost' => 35,
                'effect_type' => 'stun',
                'effect_value' => 3,
            ],

            // Aiolos du Sagittaire
            [
                'name' => 'Atomic Thunderbolt',
                'description' => 'La Foudre Atomique ! Une pluie de flèches cosmiques.',
                'damage' => 190,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Infinity Break',
                'description' => 'La Brisure de l\'Infini, transcendant les limites du cosmos.',
                'damage' => 230,
                'endurance_cost' => 55,
                'cosmos_cost' => 55,
                'effect_type' => 'buff_attack',
                'effect_value' => 25,
            ],

            // Shura du Capricorne
            [
                'name' => 'Excalibur',
                'description' => 'La légendaire épée Excalibur, capable de tout trancher.',
                'damage' => 160,
                'endurance_cost' => 35,
                'cosmos_cost' => 30,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Jumping Stone',
                'description' => 'La Pierre Sauteuse, une technique de mouvement éclair.',
                'damage' => 120,
                'endurance_cost' => 25,
                'cosmos_cost' => 25,
                'effect_type' => 'buff_attack',
                'effect_value' => 15,
            ],

            // Camus du Verseau
            [
                'name' => 'Aurora Execution',
                'description' => 'L\'Exécution de l\'Aurore, le zéro absolu.',
                'damage' => 190,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'freeze',
                'effect_value' => 3,
            ],
            [
                'name' => 'Freezing Coffin',
                'description' => 'Le Cercueil de Glace, emprisonnant l\'ennemi dans la glace éternelle.',
                'damage' => 100,
                'endurance_cost' => 40,
                'cosmos_cost' => 40,
                'effect_type' => 'freeze',
                'effect_value' => 4,
            ],

            // Aphrodite des Poissons
            [
                'name' => 'Royal Demon Rose',
                'description' => 'Les Roses Démoniaques Royales, drainant le sang de l\'ennemi.',
                'damage' => 150,
                'endurance_cost' => 35,
                'cosmos_cost' => 35,
                'effect_type' => 'drain',
                'effect_value' => 20,
            ],
            [
                'name' => 'Piranha Rose',
                'description' => 'Les Roses Piranhas, dévorant tout sur leur passage.',
                'damage' => 170,
                'endurance_cost' => 40,
                'cosmos_cost' => 40,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ],
            [
                'name' => 'Bloody Rose',
                'description' => 'La Rose Sanglante, l\'ultime rose visant le cœur.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'drain',
                'effect_value' => 30,
            ],

            // ========================================
            // ATTAQUES SPECTRES D'HADÈS
            // ========================================

            // Charon d'Achéron
            [
                'name' => 'Rolling Oar',
                'description' => 'La Rame Tourbillonnante, l\'arme mortelle du passeur des Enfers.',
                'damage' => 120,
                'endurance_cost' => 30,
                'cosmos_cost' => 25,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],
            [
                'name' => 'Eddying Current Crusher',
                'description' => 'Le Broyeur de Courant, créant un tourbillon dévastateur.',
                'damage' => 150,
                'endurance_cost' => 40,
                'cosmos_cost' => 35,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ],

            // Queen d'Alraune
            [
                'name' => 'Cursed Thorn',
                'description' => 'L\'Épine Maudite, empoisonnant l\'adversaire.',
                'damage' => 110,
                'endurance_cost' => 30,
                'cosmos_cost' => 30,
                'effect_type' => 'burn',
                'effect_value' => 20,
            ],
            [
                'name' => 'Blood Flower Scissors',
                'description' => 'Les Ciseaux de la Fleur de Sang, tranchant comme des lames.',
                'damage' => 140,
                'endurance_cost' => 35,
                'cosmos_cost' => 35,
                'effect_type' => 'drain',
                'effect_value' => 15,
            ],

            // Lune de Balron
            [
                'name' => 'Reincarnation',
                'description' => 'Réincarnation, technique manipulant l\'âme de l\'adversaire.',
                'damage' => 100,
                'endurance_cost' => 35,
                'cosmos_cost' => 40,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ],
            [
                'name' => 'Fire Whip',
                'description' => 'Le Fouet de Feu, une arme enflammée redoutable.',
                'damage' => 130,
                'endurance_cost' => 30,
                'cosmos_cost' => 30,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ],

            // Sylphide de Basilic
            [
                'name' => 'Annihilation Flap',
                'description' => 'Le Battement d\'Annihilation, créant des ondes destructrices.',
                'damage' => 145,
                'endurance_cost' => 35,
                'cosmos_cost' => 35,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Deadly Poison Breath',
                'description' => 'Le Souffle Empoisonné Mortel du Basilic.',
                'damage' => 120,
                'endurance_cost' => 30,
                'cosmos_cost' => 30,
                'effect_type' => 'burn',
                'effect_value' => 25,
            ],

            // Niobe de Deep
            [
                'name' => 'Deep Fragrance',
                'description' => 'Le Parfum Profond, endormant l\'adversaire.',
                'damage' => 90,
                'endurance_cost' => 25,
                'cosmos_cost' => 30,
                'effect_type' => 'stun',
                'effect_value' => 3,
            ],
            [
                'name' => 'Deep Sleep',
                'description' => 'Le Sommeil Profond, plongeant dans un coma éternel.',
                'damage' => 130,
                'endurance_cost' => 40,
                'cosmos_cost' => 40,
                'effect_type' => 'stun',
                'effect_value' => 4,
            ],

            // Aiacos de Garuda
            [
                'name' => 'Garuda Flap',
                'description' => 'Le Battement du Garuda, créant des tornades dévastatrices.',
                'damage' => 175,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Galactica Illusion',
                'description' => 'L\'Illusion Galactique, piégeant l\'esprit de l\'ennemi.',
                'damage' => 150,
                'endurance_cost' => 40,
                'cosmos_cost' => 45,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ],

            // Gigant du Cyclope
            [
                'name' => 'Gigantic Feathers Flap',
                'description' => 'Le Battement des Plumes Gigantesques.',
                'damage' => 160,
                'endurance_cost' => 40,
                'cosmos_cost' => 35,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],
            [
                'name' => 'Black Death Fist',
                'description' => 'Le Poing de la Mort Noire, brisant tout sur son passage.',
                'damage' => 180,
                'endurance_cost' => 45,
                'cosmos_cost' => 40,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],

            // Minos du Griffon
            [
                'name' => 'Cosmic Marionettion',
                'description' => 'La Marionnette Cosmique, contrôlant le corps de l\'ennemi.',
                'damage' => 140,
                'endurance_cost' => 45,
                'cosmos_cost' => 50,
                'effect_type' => 'stun',
                'effect_value' => 3,
            ],
            [
                'name' => 'Griffon Feathers Flap',
                'description' => 'Le Battement des Plumes du Griffon.',
                'damage' => 185,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],

            // Valentine de la Harpie
            [
                'name' => 'Greed the Life',
                'description' => 'L\'Avidité de Vie, absorbant l\'énergie vitale.',
                'damage' => 130,
                'endurance_cost' => 35,
                'cosmos_cost' => 40,
                'effect_type' => 'drain',
                'effect_value' => 25,
            ],
            [
                'name' => 'Sweet Chocolate',
                'description' => 'Doux Chocolat, une technique séductrice mortelle.',
                'damage' => 110,
                'endurance_cost' => 30,
                'cosmos_cost' => 30,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ],

            // Pandore
            [
                'name' => 'Balance of Curse',
                'description' => 'La Balance de la Malédiction, jugeant les âmes.',
                'damage' => 155,
                'endurance_cost' => 40,
                'cosmos_cost' => 45,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ],
            [
                'name' => 'Dark Serenade',
                'description' => 'La Sérénade Sombre, une mélodie envoûtante mortelle.',
                'damage' => 140,
                'endurance_cost' => 35,
                'cosmos_cost' => 40,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],

            // Myu du Papillon
            [
                'name' => 'Fairy Thronging',
                'description' => 'L\'Essaim de Fées, une nuée de papillons mortels.',
                'damage' => 135,
                'endurance_cost' => 35,
                'cosmos_cost' => 35,
                'effect_type' => 'burn',
                'effect_value' => 15,
            ],
            [
                'name' => 'Silky Thread',
                'description' => 'Le Fil de Soie, emprisonnant l\'adversaire.',
                'damage' => 100,
                'endurance_cost' => 30,
                'cosmos_cost' => 30,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],
            [
                'name' => 'Ugly Eruption',
                'description' => 'L\'Éruption Hideuse, révélant la vraie forme du Papillon.',
                'damage' => 170,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],

            // Pharaoh du Sphinx
            [
                'name' => 'Balance of Curse Sphinx',
                'description' => 'La Balance de la Malédiction, pesant les âmes des défunts.',
                'damage' => 160,
                'endurance_cost' => 40,
                'cosmos_cost' => 45,
                'effect_type' => 'debuff',
                'effect_value' => 25,
            ],
            [
                'name' => 'Harp of Hades',
                'description' => 'La Harpe d\'Hadès, une mélodie funeste.',
                'damage' => 140,
                'endurance_cost' => 35,
                'cosmos_cost' => 40,
                'effect_type' => 'stun',
                'effect_value' => 2,
            ],

            // Rhadamanthe de la Wyvern
            [
                'name' => 'Greatest Caution',
                'description' => 'La Plus Grande Prudence ! L\'attaque suprême de Rhadamanthe.',
                'damage' => 185,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ],

            // ========================================
            // ATTAQUES DES DIEUX
            // ========================================

            // Athéna
            [
                'name' => 'Athena Exclamation',
                'description' => 'L\'Exclamation d\'Athéna, technique interdite des Chevaliers d\'Or.',
                'damage' => 280,
                'endurance_cost' => 70,
                'cosmos_cost' => 70,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Aegis Shield',
                'description' => 'Le Bouclier Égide, protection divine absolue.',
                'damage' => 0,
                'endurance_cost' => 40,
                'cosmos_cost' => 50,
                'effect_type' => 'buff_defense',
                'effect_value' => 100,
            ],
            [
                'name' => 'Nike Staff',
                'description' => 'Le Bâton de Niké, canalisant le cosmos divin d\'Athéna.',
                'damage' => 180,
                'endurance_cost' => 45,
                'cosmos_cost' => 50,
                'effect_type' => 'heal',
                'effect_value' => 50,
            ],
            [
                'name' => 'Athena Blessing',
                'description' => 'La bénédiction d\'Athéna restaure la vie de l\'allié.',
                'damage' => 0,
                'endurance_cost' => 40,
                'cosmos_cost' => 35,
                'effect_type' => 'heal',
                'effect_value' => 80,
            ],

            // Hadès
            [
                'name' => 'Terrible Providence',
                'description' => 'La Providence Terrible d\'Hadès, anéantissant tout.',
                'damage' => 250,
                'endurance_cost' => 60,
                'cosmos_cost' => 60,
                'effect_type' => 'drain',
                'effect_value' => 30,
            ],
            [
                'name' => 'Greatest Eclipse',
                'description' => 'La Plus Grande Éclipse, plongeant le monde dans les ténèbres.',
                'damage' => 220,
                'endurance_cost' => 55,
                'cosmos_cost' => 55,
                'effect_type' => 'debuff',
                'effect_value' => 35,
            ],
            [
                'name' => 'Sword of Hades',
                'description' => 'L\'Épée d\'Hadès, l\'arme du dieu des Enfers.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'drain',
                'effect_value' => 25,
            ],

            // Poséidon
            [
                'name' => 'Poseidon\'s Wrath',
                'description' => 'La Colère de Poséidon, déchaînant les océans.',
                'damage' => 240,
                'endurance_cost' => 55,
                'cosmos_cost' => 55,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
            [
                'name' => 'Tidal Wave',
                'description' => 'Le Raz-de-Marée, submergeant tout sur son passage.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'stun',
                'effect_value' => 3,
            ],
            [
                'name' => 'Trident Strike',
                'description' => 'Le Coup du Trident, l\'arme divine du dieu des mers.',
                'damage' => 180,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'freeze',
                'effect_value' => 2,
            ],

            // Hypnos
            [
                'name' => 'Eternal Drowsiness',
                'description' => 'La Somnolence Éternelle, plongeant dans un sommeil sans fin.',
                'damage' => 180,
                'endurance_cost' => 50,
                'cosmos_cost' => 55,
                'effect_type' => 'stun',
                'effect_value' => 5,
            ],
            [
                'name' => 'Encounter Another Field',
                'description' => 'Rencontre dans un Autre Champ, projection dans les rêves.',
                'damage' => 200,
                'endurance_cost' => 55,
                'cosmos_cost' => 55,
                'effect_type' => 'debuff',
                'effect_value' => 30,
            ],

            // Thanatos
            [
                'name' => 'Thanatos Terrible Providence',
                'description' => 'La Providence Terrible de Thanatos, main de la mort.',
                'damage' => 230,
                'endurance_cost' => 55,
                'cosmos_cost' => 55,
                'effect_type' => 'drain',
                'effect_value' => 25,
            ],
            [
                'name' => 'Tartarus Phobia',
                'description' => 'La Phobie du Tartare, invoquant les terreurs des Enfers.',
                'damage' => 210,
                'endurance_cost' => 50,
                'cosmos_cost' => 55,
                'effect_type' => 'debuff',
                'effect_value' => 35,
            ],
            [
                'name' => 'Death Touch',
                'description' => 'Le Toucher de la Mort, fauche instantanée.',
                'damage' => 250,
                'endurance_cost' => 60,
                'cosmos_cost' => 60,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
        ];

        foreach ($attacks as $attack) {
            Attack::firstOrCreate(
                ['name' => $attack['name']],
                $attack
            );
        }
    }
}
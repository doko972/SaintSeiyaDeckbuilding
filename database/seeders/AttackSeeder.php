<?php

namespace Database\Seeders;

use App\Models\Attack;
use Illuminate\Database\Seeder;

class AttackSeeder extends Seeder
{
    public function run(): void
    {
        $attacks = [
            // Attaques Bronze
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
                'name' => 'Rozan Shō Ryū Ha',
                'description' => 'Le Dragon Ascendant ! La puissance du dragon de Rozan.',
                'damage' => 90,
                'endurance_cost' => 25,
                'cosmos_cost' => 20,
                'effect_type' => 'buff_attack',
                'effect_value' => 10,
            ],
            [
                'name' => 'Rozan Hyaku Ryū Ha',
                'description' => 'Les Cent Dragons de Rozan ! Une attaque ultime libérant la puissance maximale.',
                'damage' => 150,
                'endurance_cost' => 50,
                'cosmos_cost' => 45,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
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
                'name' => 'Thunder Claw',
                'description' => 'Les griffes foudroyantes du Phénix.',
                'damage' => 85,
                'endurance_cost' => 20,
                'cosmos_cost' => 20,
                'effect_type' => 'burn',
                'effect_value' => 10,
            ],
            [
                'name' => 'Phoenix Genma Ken',
                'description' => 'L\'Illusion Démoniaque du Phénix détruit l\'esprit de l\'adversaire.',
                'damage' => 60,
                'endurance_cost' => 30,
                'cosmos_cost' => 35,
                'effect_type' => 'debuff',
                'effect_value' => 20,
            ],

            // Attaques Or
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
                'name' => 'Galaxian Explosion',
                'description' => 'L\'Explosion Galactique ! L\'attaque ultime des Gémeaux.',
                'damage' => 200,
                'endurance_cost' => 50,
                'cosmos_cost' => 50,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
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
                'name' => 'Excalibur',
                'description' => 'La légendaire épée Excalibur, capable de tout trancher.',
                'damage' => 160,
                'endurance_cost' => 35,
                'cosmos_cost' => 30,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],
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
                'name' => 'Stardust Revolution',
                'description' => 'La Révolution Stellaire, une pluie d\'étoiles dévastatrice.',
                'damage' => 170,
                'endurance_cost' => 40,
                'cosmos_cost' => 40,
                'effect_type' => 'none',
                'effect_value' => 0,
            ],

            // Attaques Spectres
            [
                'name' => 'Greatest Caution',
                'description' => 'L\'attaque suprême de Rhadamanthe, une explosion de cosmos sombre.',
                'damage' => 185,
                'endurance_cost' => 45,
                'cosmos_cost' => 45,
                'effect_type' => 'debuff',
                'effect_value' => 15,
            ],
            [
                'name' => 'Terrible Providence',
                'description' => 'La Providence Terrible d\'Hadès.',
                'damage' => 250,
                'endurance_cost' => 60,
                'cosmos_cost' => 60,
                'effect_type' => 'drain',
                'effect_value' => 30,
            ],

            // Attaques de soin
            [
                'name' => 'Crystal Wall',
                'description' => 'Le Mur de Cristal, une barrière protectrice qui restaure l\'énergie.',
                'damage' => 0,
                'endurance_cost' => 30,
                'cosmos_cost' => 25,
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
        ];

        foreach ($attacks as $attack) {
            Attack::create($attack);
        }
    }
}
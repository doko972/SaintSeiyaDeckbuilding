<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Faction;
use App\Models\Attack;
use Illuminate\Database\Seeder;

class PersephoneSeeder extends Seeder
{
    public function run(): void
    {
        $spectres = Faction::where('name', 'Spectres d\'Hadès')->first();

        if (!$spectres) {
            $this->command->error('Faction "Spectres d\'Hadès" non trouvée!');
            return;
        }

        $attacks = Attack::pluck('id', 'name')->toArray();

        // Vérifier que les attaques existent
        $mainAttack = $attacks['Malédiction de la Grenade'] ?? null;
        $secondaryAttack1 = $attacks['Lamentation de Koré'] ?? null;
        $secondaryAttack2 = $attacks['Floraison du Narcisse'] ?? null;

        if (!$mainAttack) {
            $this->command->error('Attaque "Malédiction de la Grenade" non trouvée. Créez-la d\'abord via le formulaire.');
            return;
        }

        Card::firstOrCreate(
            ['name' => 'Persephone'],
            [
                'name' => 'Persephone',
                'faction_id' => $spectres->id,
                'grade' => 10,
                'armor_type' => 'divine',
                'element' => 'darkness',
                'rarity' => 'legendary',
                'health_points' => 265,
                'endurance' => 125,
                'defense' => 52,
                'power' => 92,
                'cosmos' => 145,
                'cost' => 10,
                'passive_ability_name' => 'Reine des Enfers',
                'passive_ability_description' => 'Draine 10% des PV max des ennemis chaque tour. Quand Hades est present, +25% a toutes les stats.',
                'main_attack_id' => $mainAttack,
                'secondary_attack_1_id' => $secondaryAttack1,
                'secondary_attack_2_id' => $secondaryAttack2,
            ]
        );

        $this->command->info('Persephone, Reine des Enfers, a ete creee avec succes!');
    }
}

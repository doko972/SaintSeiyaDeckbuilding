<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            FactionSeeder::class,
            AttackSeeder::class,
            CardSeeder::class,
            AsgardWarriorsSeeder::class,
            PoseidonMarinasSeeder::class,
            BlackSaintsSeeder::class,
            BronzeSaintsSecondarySeeder::class,
            SpectresSecondarySeeder::class,
            SteelSaintsSeeder::class,
            SilverSaintsSeeder::class,
            GoldSpectresSeeder::class,
        ]);
    }

    
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@saint-seiya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer un utilisateur test
        User::create([
            'name' => 'Joueur Test',
            'email' => 'player@saint-seiya.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
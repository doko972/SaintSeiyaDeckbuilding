<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Met à jour les rangs des joueurs existants en fonction de leurs victoires
     */
    public function up(): void
    {
        // Chevalier Divin : 100+ wins
        DB::table('users')
            ->where('wins', '>=', 100)
            ->update(['current_rank' => 'divin']);

        // Chevalier d'Or : 50-99 wins
        DB::table('users')
            ->where('wins', '>=', 50)
            ->where('wins', '<', 100)
            ->update(['current_rank' => 'or']);

        // Chevalier d'Argent : 20-49 wins
        DB::table('users')
            ->where('wins', '>=', 20)
            ->where('wins', '<', 50)
            ->update(['current_rank' => 'argent']);

        // Chevalier de Bronze : moins de 20 wins (déjà la valeur par défaut)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre tout le monde en bronze
        DB::table('users')->update(['current_rank' => 'bronze']);
    }
};

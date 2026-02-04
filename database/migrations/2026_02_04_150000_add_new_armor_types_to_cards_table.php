<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter les nouveaux types d'armure : black, god_warrior, steel
        DB::statement("ALTER TABLE cards MODIFY COLUMN armor_type ENUM('black', 'bronze', 'silver', 'gold', 'divine', 'surplis', 'god_warrior', 'steel') DEFAULT 'bronze'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Attention : les donnees avec les nouveaux types seront perdues
        DB::statement("ALTER TABLE cards MODIFY COLUMN armor_type ENUM('bronze', 'silver', 'gold', 'divine', 'surplis') DEFAULT 'bronze'");
    }
};

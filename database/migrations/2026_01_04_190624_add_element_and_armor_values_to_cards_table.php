<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'ENUM element pour ajouter 'earth'
        DB::statement("ALTER TABLE cards MODIFY COLUMN element ENUM('fire', 'water', 'ice', 'thunder', 'darkness', 'light', 'earth') DEFAULT 'light'");

        // Modifier l'ENUM armor_type pour ajouter 'surplis'
        DB::statement("ALTER TABLE cards MODIFY COLUMN armor_type ENUM('bronze', 'silver', 'gold', 'divine', 'surplis') DEFAULT 'bronze'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les valeurs originales (attention : les données avec earth/surplis seront perdues)
        DB::statement("ALTER TABLE cards MODIFY COLUMN element ENUM('fire', 'water', 'ice', 'thunder', 'darkness', 'light') DEFAULT 'light'");

        DB::statement("ALTER TABLE cards MODIFY COLUMN armor_type ENUM('bronze', 'silver', 'gold', 'divine') DEFAULT 'bronze'");
    }
};
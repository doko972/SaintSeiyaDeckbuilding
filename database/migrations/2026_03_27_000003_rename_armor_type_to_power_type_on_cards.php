<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Méthode add/copy/drop pour ENUM MySQL (renameColumn ne supporte pas les ENUM)
        DB::statement("ALTER TABLE cards ADD COLUMN power_type
            ENUM('black','bronze','silver','gold','divine','surplis','god_warrior','steel')
            NOT NULL DEFAULT 'bronze' AFTER armor_type");

        DB::statement("UPDATE cards SET power_type = armor_type");

        DB::statement("ALTER TABLE cards DROP COLUMN armor_type");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cards ADD COLUMN armor_type
            ENUM('black','bronze','silver','gold','divine','surplis','god_warrior','steel')
            NOT NULL DEFAULT 'bronze' AFTER power_type");

        DB::statement("UPDATE cards SET armor_type = power_type");

        DB::statement("ALTER TABLE cards DROP COLUMN power_type");
    }
};

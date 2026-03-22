<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->enum('passive_effect_type', [
                'none',
                'heal_allies',   // Soigne les alliés au déploiement
                'shield_self',   // Gagne de la défense au déploiement
                'boost_allies',  // Augmente la puissance des alliés au déploiement
            ])->default('none')->after('passive_ability_description');

            $table->unsignedInteger('passive_effect_value')->default(0)->after('passive_effect_type');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['passive_effect_type', 'passive_effect_value']);
        });
    }
};

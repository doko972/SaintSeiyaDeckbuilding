<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attacks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedInteger('damage');
            $table->unsignedInteger('endurance_cost')->default(0);
            $table->unsignedInteger('cosmos_cost')->default(0);
            $table->enum('effect_type', [
                'none',
                'burn',        // Brûlure (dégâts sur la durée)
                'freeze',      // Gel (immobilise)
                'stun',        // Étourdit
                'heal',        // Soigne
                'buff_attack', // Augmente l'attaque
                'buff_defense',// Augmente la défense
                'debuff',      // Réduit les stats ennemies
                'drain'        // Vole des PV
            ])->default('none');
            $table->unsignedInteger('effect_value')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attacks');
    }
};
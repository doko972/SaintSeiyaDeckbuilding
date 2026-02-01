<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            // Les 3 cartes requises pour activer le combo
            $table->foreignId('card1_id')->constrained('cards')->onDelete('cascade');
            $table->foreignId('card2_id')->constrained('cards')->onDelete('cascade');
            $table->foreignId('card3_id')->constrained('cards')->onDelete('cascade');

            // La carte qui peut lancer l'attaque combo (doit être une des 3)
            $table->foreignId('leader_card_id')->constrained('cards')->onDelete('cascade');

            // L'attaque spéciale débloquée par le combo
            $table->foreignId('attack_id')->constrained('attacks')->onDelete('cascade');

            // Coûts supplémentaires pour utiliser l'attaque combo
            $table->integer('endurance_cost')->default(0);
            $table->integer('cosmos_cost')->default(0);

            // Statut actif/inactif
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};

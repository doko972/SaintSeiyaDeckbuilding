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
        Schema::table('users', function (Blueprint $table) {
            // Indique si l'utilisateur a déjà sélectionné son Bronze de départ
            $table->boolean('has_selected_starter')->default(false)->after('role');
            
            // ID de la carte Bronze choisie (nullable car pas encore sélectionné au départ)
            $table->foreignId('starter_bronze_id')
                ->nullable()
                ->after('has_selected_starter')
                ->constrained('cards')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la clé étrangère d'abord
            $table->dropForeign(['starter_bronze_id']);
            
            // Puis supprimer les colonnes
            $table->dropColumn(['has_selected_starter', 'starter_bronze_id']);
        });
    }
};
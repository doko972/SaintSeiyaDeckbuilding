<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            
            // Relation avec faction
            $table->foreignId('faction_id')->constrained()->onDelete('cascade');
            
            // Caractéristiques
            $table->unsignedTinyInteger('grade')->default(1);        // 1 à 10
            $table->enum('armor_type', ['bronze', 'silver', 'gold', 'divine'])->default('bronze');
            $table->enum('element', ['fire', 'water', 'ice', 'thunder', 'darkness', 'light'])->default('light');
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary'])->default('common');
            
            // Stats
            $table->unsignedInteger('health_points');
            $table->unsignedInteger('endurance');
            $table->unsignedInteger('defense');
            $table->unsignedInteger('power');
            $table->unsignedInteger('cosmos');
            $table->unsignedInteger('cost');  // Coût d'invocation
            
            // Capacité passive
            $table->string('passive_ability_name', 100)->nullable();
            $table->text('passive_ability_description')->nullable();
            
            // Attaques (clés étrangères)
            $table->foreignId('main_attack_id')->constrained('attacks')->onDelete('cascade');
            $table->foreignId('secondary_attack_1_id')->nullable()->constrained('attacks')->onDelete('set null');
            $table->foreignId('secondary_attack_2_id')->nullable()->constrained('attacks')->onDelete('set null');
            
            // Images
            $table->string('image_primary')->nullable();
            $table->string('image_secondary')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
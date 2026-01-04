<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_deck', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->foreignId('deck_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('quantity')->default(1);  // Nombre d'exemplaires
            $table->timestamps();
            
            // Empêche les doublons card/deck
            $table->unique(['card_id', 'deck_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_deck');
    }
};
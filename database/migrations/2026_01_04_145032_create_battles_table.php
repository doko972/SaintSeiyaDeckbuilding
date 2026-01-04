<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('player2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('player1_deck_id')->constrained('decks')->onDelete('cascade');
            $table->foreignId('player2_deck_id')->nullable()->constrained('decks')->onDelete('cascade');
            $table->json('battle_state')->nullable();
            $table->enum('status', ['waiting', 'in_progress', 'finished', 'cancelled'])->default('waiting');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('current_turn_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('turn_number')->default(1);
            $table->timestamp('last_action_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['player1_id', 'status']);
            $table->index(['player2_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};
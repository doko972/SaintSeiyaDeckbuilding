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
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->integer('round');
            $table->integer('match_number');
            $table->string('bracket_code')->nullable();

            $table->foreignId('participant1_id')->nullable()->constrained('tournament_participants')->onDelete('set null');
            $table->foreignId('participant2_id')->nullable()->constrained('tournament_participants')->onDelete('set null');

            $table->foreignId('battle_id')->nullable()->constrained('battles')->onDelete('set null');
            $table->foreignId('winner_participant_id')->nullable()->constrained('tournament_participants')->onDelete('set null');

            $table->enum('status', ['pending', 'ready', 'in_progress', 'finished', 'bye'])->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->unsignedBigInteger('next_match_id')->nullable();
            $table->timestamps();

            $table->index(['tournament_id', 'round']);
            $table->index(['tournament_id', 'status']);
            $table->unique(['tournament_id', 'round', 'match_number']);

            $table->foreign('next_match_id')->references('id')->on('tournament_matches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};

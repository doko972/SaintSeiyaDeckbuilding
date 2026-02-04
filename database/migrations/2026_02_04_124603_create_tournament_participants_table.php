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
        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('deck_id')->constrained()->onDelete('cascade');

            $table->integer('seed_position')->nullable();
            $table->integer('bracket_position')->nullable();
            $table->enum('status', ['registered', 'active', 'eliminated', 'winner', 'disqualified'])->default('registered');
            $table->integer('final_rank')->nullable();
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);

            $table->timestamp('registered_at')->nullable();
            $table->timestamp('eliminated_at')->nullable();
            $table->timestamps();

            $table->unique(['tournament_id', 'user_id']);
            $table->index(['tournament_id', 'status']);
            $table->index(['tournament_id', 'bracket_position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_participants');
    }
};

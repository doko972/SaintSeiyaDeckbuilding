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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['single_elimination'])->default('single_elimination');
            $table->enum('status', ['draft', 'registration', 'in_progress', 'finished', 'cancelled'])->default('draft');
            $table->integer('max_players')->default(8);
            $table->integer('current_round')->default(0);
            $table->integer('total_rounds')->default(3);

            // Dates
            $table->timestamp('registration_start_at')->nullable();
            $table->timestamp('registration_end_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            // Configuration recompenses (JSON)
            $table->json('rewards_config')->nullable();

            // Parametres
            $table->integer('min_deck_cards')->default(10);
            $table->integer('entry_fee')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->index('status');
            $table->index(['status', 'registration_start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};

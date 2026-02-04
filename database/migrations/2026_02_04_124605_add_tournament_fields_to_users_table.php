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
            $table->integer('tournament_wins')->default(0)->after('losses');
            $table->string('tournament_title')->nullable()->after('tournament_wins');
            $table->integer('tournament_points')->default(0)->after('tournament_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tournament_wins', 'tournament_title', 'tournament_points']);
        });
    }
};

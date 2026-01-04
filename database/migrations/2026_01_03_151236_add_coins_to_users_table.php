<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('coins')->default(500)->after('role'); // Monnaie du jeu
            $table->unsignedInteger('wins')->default(0)->after('coins');   // Victoires
            $table->unsignedInteger('losses')->default(0)->after('wins');  // Défaites
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['coins', 'wins', 'losses']);
        });
    }
};
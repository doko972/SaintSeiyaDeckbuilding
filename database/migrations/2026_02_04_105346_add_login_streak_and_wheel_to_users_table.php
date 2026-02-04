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
            // Série de connexion
            $table->unsignedTinyInteger('login_streak')->default(0)->after('last_daily_bonus_at');
            $table->date('last_login_date')->nullable()->after('login_streak');
            $table->boolean('streak_reward_claimed_today')->default(false)->after('last_login_date');

            // Roue de la fortune hebdomadaire
            $table->timestamp('last_wheel_spin_at')->nullable()->after('streak_reward_claimed_today');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'login_streak',
                'last_login_date',
                'streak_reward_claimed_today',
                'last_wheel_spin_at',
            ]);
        });
    }
};

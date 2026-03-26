<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factions', function (Blueprint $table) {
            $table->foreignId('universe_id')->nullable()->after('id')
                  ->constrained('universes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('factions', function (Blueprint $table) {
            $table->dropForeign(['universe_id']);
            $table->dropColumn('universe_id');
        });
    }
};

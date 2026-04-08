<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE attacks MODIFY COLUMN effect_type ENUM(
            'none','burn','freeze','stun','heal','buff_attack','buff_defense','debuff','drain','regen'
        ) DEFAULT 'none'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE attacks MODIFY COLUMN effect_type ENUM(
            'none','burn','freeze','stun','heal','buff_attack','buff_defense','debuff','drain'
        ) DEFAULT 'none'");
    }
};

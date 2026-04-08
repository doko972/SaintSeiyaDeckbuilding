<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('fusion_level'); // 1 à 10
            $table->string('image_primary')->nullable();
            $table->string('image_secondary')->nullable();
            $table->timestamps();

            $table->unique(['card_id', 'fusion_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_images');
    }
};

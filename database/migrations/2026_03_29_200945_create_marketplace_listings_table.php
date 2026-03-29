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
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('card_id')->constrained('cards')->cascadeOnDelete();
            $table->unsignedInteger('starting_price');         // Prix de départ des enchères
            $table->unsignedInteger('buyout_price');           // Prix d'achat immédiat
            $table->unsignedInteger('current_bid')->default(0); // Enchère actuelle
            $table->foreignId('current_bidder_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'sold', 'traded', 'expired', 'cancelled'])->default('active');
            $table->timestamp('expires_at');                   // +48h à la création
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_listings');
    }
};

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
        Schema::create('mailbox_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // Destinataire
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete(); // Expéditeur (null = système)
            $table->enum('type', [
                'bid_received',       // Quelqu'un a enchéri sur ton annonce
                'outbid',             // Tu as été surenchéri
                'auction_won',        // Tu as gagné l'enchère
                'auction_sold',       // Ta carte a été vendue (enchère)
                'buyout',             // Ta carte a été achetée immédiatement
                'offer_received',     // Offre d'échange reçue
                'offer_accepted',     // Ton offre d'échange a été acceptée
                'offer_rejected',     // Ton offre d'échange a été refusée
                'listing_expired',    // Ton annonce a expiré sans vente
                'system',             // Message système générique
            ]);
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();                  // Données liées (listing_id, card_id, amount…)
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailbox_messages');
    }
};

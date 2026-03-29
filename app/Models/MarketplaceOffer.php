<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceOffer extends Model
{
    protected $fillable = [
        'listing_id',
        'offerer_id',
        'offered_card_id',
        'status',
        'message',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(MarketplaceListing::class, 'listing_id');
    }

    public function offerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'offerer_id');
    }

    public function offeredCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'offered_card_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}

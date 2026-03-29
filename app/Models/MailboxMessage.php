<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailboxMessage extends Model
{
    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'title',
        'body',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    // Icônes par type de message
    public function icon(): string
    {
        return match($this->type) {
            'bid_received'    => '🔔',
            'outbid'          => '⚠️',
            'auction_won'     => '🏆',
            'auction_sold'    => '💰',
            'buyout'          => '💰',
            'offer_received'  => '🤝',
            'offer_accepted'  => '✅',
            'offer_rejected'  => '❌',
            'listing_expired' => '⏰',
            default           => '📬',
        };
    }
}

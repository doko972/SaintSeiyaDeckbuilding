<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceListing extends Model
{
    protected $fillable = [
        'seller_id',
        'card_id',
        'starting_price',
        'buyout_price',
        'current_bid',
        'current_bidder_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'current_bid' => 'integer',
    ];

    // Prix minimum de départ par rareté
    const MIN_PRICES = [
        'common'    => 50,
        'rare'      => 150,
        'epic'      => 400,
        'legendary' => 1000,
        'mythic'    => 3000,
    ];

    const COMMISSION_RATE = 0.10; // 10%
    const DURATION_HOURS  = 48;

    // ── Relations ────────────────────────────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function currentBidder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_bidder_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(MarketplaceBid::class, 'listing_id')->orderByDesc('amount');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(MarketplaceOffer::class, 'listing_id');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'active' && $this->expires_at->isPast();
    }

    public function timeRemaining(): string
    {
        if (!$this->isActive()) return 'Terminée';
        $diff = now()->diff($this->expires_at);
        if ($diff->h > 0 || $diff->days > 0) {
            $hours = $diff->days * 24 + $diff->h;
            return $hours . 'h ' . $diff->i . 'min';
        }
        return $diff->i . 'min ' . $diff->s . 's';
    }

    public function nextMinimumBid(): int
    {
        if ($this->current_bid === 0) return $this->starting_price;
        return (int) ceil($this->current_bid * 1.05); // +5% minimum
    }

    public function commission(int $amount): int
    {
        return (int) ceil($amount * self::COMMISSION_RATE);
    }

    public function sellerReceives(int $amount): int
    {
        return $amount - $this->commission($amount);
    }
}

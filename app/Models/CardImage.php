<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardImage extends Model
{
    protected $fillable = [
        'card_id',
        'fusion_level',
        'image_primary',
        'image_secondary',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}

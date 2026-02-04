<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PvpInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'deck_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Duree de validite d'une invitation en secondes (60 secondes)
     */
    public const EXPIRATION_SECONDS = 60;

    /**
     * L'utilisateur qui envoie l'invitation
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * L'utilisateur qui recoit l'invitation
     */
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Le deck utilise pour le combat
     */
    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    /**
     * Scope: invitations en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '>', now());
    }

    /**
     * Scope: invitations pour un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('to_user_id', $userId);
    }

    /**
     * Scope: invitations envoyees par un utilisateur
     */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('from_user_id', $userId);
    }

    /**
     * Verifie si l'invitation est expiree
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Verifie si l'invitation est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Accepte l'invitation
     */
    public function accept(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update(['status' => 'accepted']);
        return true;
    }

    /**
     * Decline l'invitation
     */
    public function decline(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update(['status' => 'declined']);
        return true;
    }

    /**
     * Annule l'invitation
     */
    public function cancel(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update(['status' => 'cancelled']);
        return true;
    }

    /**
     * Marque les invitations expirees
     */
    public static function expireOldInvitations(): int
    {
        return static::where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);
    }
}

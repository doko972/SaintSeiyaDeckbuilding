<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;

class DeckPolicy
{
    /**
     * L'utilisateur peut voir son propre deck
     */
    public function view(User $user, Deck $deck): bool
    {
        return $user->id === $deck->user_id;
    }

    /**
     * L'utilisateur peut modifier son propre deck
     */
    public function update(User $user, Deck $deck): bool
    {
        return $user->id === $deck->user_id;
    }

    /**
     * L'utilisateur peut supprimer son propre deck
     */
    public function delete(User $user, Deck $deck): bool
    {
        return $user->id === $deck->user_id;
    }
}
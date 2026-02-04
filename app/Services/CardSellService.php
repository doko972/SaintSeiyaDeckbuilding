<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CardSellService
{
    /**
     * Prix de revente par rareté
     */
    public const SELL_PRICES = [
        'common' => 5,
        'rare' => 15,
        'epic' => 35,
        'legendary' => 75,
        'mythic' => 150,
    ];

    /**
     * Obtient le prix de revente d'une carte
     */
    public function getSellPrice(Card $card): int
    {
        return self::SELL_PRICES[$card->rarity] ?? 5;
    }

    /**
     * Vérifie si une carte peut être vendue
     *
     * @param User $user
     * @param int $cardId
     * @param int $quantity Quantité à vendre
     * @return array ['can_sell' => bool, 'reason' => string|null, 'price' => int]
     */
    public function canSell(User $user, int $cardId, int $quantity = 1): array
    {
        $userCard = $user->cards()->where('card_id', $cardId)->first();

        if (!$userCard) {
            return [
                'can_sell' => false,
                'reason' => 'Vous ne possédez pas cette carte.',
                'price' => 0,
            ];
        }

        $ownedQuantity = $userCard->pivot->quantity ?? 0;

        if ($quantity < 1) {
            return [
                'can_sell' => false,
                'reason' => 'La quantité doit être supérieure à 0.',
                'price' => 0,
            ];
        }

        if ($ownedQuantity < $quantity) {
            return [
                'can_sell' => false,
                'reason' => "Vous n'avez que {$ownedQuantity} exemplaire(s) de cette carte.",
                'price' => 0,
            ];
        }

        $pricePerCard = self::SELL_PRICES[$userCard->rarity] ?? 5;
        $totalPrice = $pricePerCard * $quantity;

        return [
            'can_sell' => true,
            'reason' => null,
            'price' => $totalPrice,
            'price_per_card' => $pricePerCard,
        ];
    }

    /**
     * Exécute la vente d'une carte
     *
     * @param User $user
     * @param int $cardId
     * @param int $quantity Quantité à vendre
     * @return array ['success' => bool, 'coins_earned' => int, 'reason' => string|null]
     */
    public function sellCard(User $user, int $cardId, int $quantity = 1): array
    {
        $canSell = $this->canSell($user, $cardId, $quantity);

        if (!$canSell['can_sell']) {
            return [
                'success' => false,
                'reason' => $canSell['reason'],
                'coins_earned' => 0,
            ];
        }

        $card = Card::findOrFail($cardId);
        $userCard = $user->cards()->where('card_id', $cardId)->first();
        $currentQuantity = $userCard->pivot->quantity;
        $coinsEarned = $canSell['price'];

        DB::transaction(function () use ($user, $cardId, $quantity, $currentQuantity, $coinsEarned) {
            // Ajouter les pièces
            $user->increment('coins', $coinsEarned);

            // Réduire la quantité ou supprimer la carte
            $newQuantity = $currentQuantity - $quantity;

            if ($newQuantity <= 0) {
                // Supprimer complètement la carte de la collection
                $user->cards()->detach($cardId);
            } else {
                // Mettre à jour la quantité
                $user->cards()->updateExistingPivot($cardId, [
                    'quantity' => $newQuantity,
                ]);
            }
        });

        $user->refresh();

        return [
            'success' => true,
            'reason' => null,
            'coins_earned' => $coinsEarned,
            'new_balance' => $user->coins,
            'card_name' => $card->name,
            'quantity_sold' => $quantity,
        ];
    }

    /**
     * Aperçu de la vente
     *
     * @param User $user
     * @param int $cardId
     * @return array
     */
    public function getSellPreview(User $user, int $cardId): array
    {
        $card = Card::with('faction')->findOrFail($cardId);
        $userCard = $user->cards()->where('card_id', $cardId)->first();

        if (!$userCard) {
            return ['error' => 'Carte non trouvée dans votre collection'];
        }

        $quantity = $userCard->pivot->quantity ?? 0;
        $pricePerCard = self::SELL_PRICES[$card->rarity] ?? 5;

        return [
            'card' => [
                'id' => $card->id,
                'name' => $card->name,
                'rarity' => $card->rarity,
                'image' => $card->image_primary,
                'faction' => $card->faction?->name,
            ],
            'quantity' => $quantity,
            'price_per_card' => $pricePerCard,
            'max_earnings' => $pricePerCard * $quantity,
            'user_coins' => $user->coins,
        ];
    }
}

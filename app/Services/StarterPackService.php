<?php

namespace App\Services;

use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StarterPackService
{
    /**
     * Configuration du starter pack
     */
    private const STARTER_COINS = 500;
    private const SOLDIER_QUANTITY = 10;
    private const GUARD_QUANTITY = 6;
    private const SUPPORT_QUANTITY = 3;
    private const BRONZE_QUANTITY = 1;

    /**
     * Distribue le starter pack complet à un nouvel utilisateur
     * 
     * @param User $user
     * @param int $bronzeCardId ID de la carte Bronze choisie
     * @return bool
     */
    public function distributeStarterPack(User $user, int $bronzeCardId): bool
    {
        // Vérifier que l'utilisateur n'a pas déjà reçu son starter pack
        if ($user->has_selected_starter) {
            return false;
        }

        // Vérifier que la carte Bronze existe et est bien un Bronze rare
        $bronzeCard = Card::find($bronzeCardId);
        if (!$bronzeCard || !$this->isValidBronzeStarter($bronzeCard)) {
            return false;
        }

        DB::beginTransaction();
        
        try {
            // 1. Enregistrer le choix du Bronze
            $user->update([
                'has_selected_starter' => true,
                'starter_bronze_id' => $bronzeCardId,
                'coins' => self::STARTER_COINS,
            ]);

            // 2. Ajouter le Bronze choisi
            $user->addCard($bronzeCard, self::BRONZE_QUANTITY);

            // 3. Ajouter les Soldats du Sanctuaire
            $soldier = Card::where('name', 'Soldat du Sanctuaire')->first();
            if ($soldier) {
                $user->addCard($soldier, self::SOLDIER_QUANTITY);
            }

            // 4. Ajouter les Gardes d'Argent
            $guard = Card::where('name', 'Garde d\'Argent')->first();
            if ($guard) {
                $user->addCard($guard, self::GUARD_QUANTITY);
            }

            // 5. Ajouter une carte de soutien (Crystal Wall - défense)
            $crystalWall = Card::whereHas('mainAttack', function ($query) {
                $query->where('name', 'Crystal Wall');
            })->first();
            
            if ($crystalWall) {
                $user->addCard($crystalWall, self::SUPPORT_QUANTITY);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la distribution du starter pack: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère la liste des Bronzes disponibles pour la sélection
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableBronzeStarters()
    {
        return Card::where('armor_type', 'bronze')
            ->where('rarity', 'rare')
            ->whereIn('name', [
                'Seiya de Pegase',
                'Hyoga du Cygne',
                'Shiryu du Dragon',
                'Shun d\'Andromede',
                'Ikki du Phenix'
            ])
            ->with(['faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2'])
            ->get();
    }

    /**
     * Vérifie si une carte est un Bronze valide pour le starter
     * 
     * @param Card $card
     * @return bool
     */
    private function isValidBronzeStarter(Card $card): bool
    {
        $validBronzes = [
            'Seiya de Pegase',
            'Hyoga du Cygne',
            'Shiryu du Dragon',
            'Shun d\'Andromede',
            'Ikki du Phenix'
        ];

        return $card->armor_type === 'bronze' 
            && $card->rarity === 'rare' 
            && in_array($card->name, $validBronzes);
    }

    /**
     * Récupère les détails du starter pack
     * 
     * @return array
     */
    public function getStarterPackDetails(): array
    {
        return [
            'coins' => self::STARTER_COINS,
            'cards' => [
                [
                    'name' => 'Bronze Rare (au choix)',
                    'quantity' => self::BRONZE_QUANTITY,
                    'rarity' => 'rare'
                ],
                [
                    'name' => 'Soldat du Sanctuaire',
                    'quantity' => self::SOLDIER_QUANTITY,
                    'rarity' => 'common'
                ],
                [
                    'name' => 'Garde d\'Argent',
                    'quantity' => self::GUARD_QUANTITY,
                    'rarity' => 'common'
                ],
                [
                    'name' => 'Carte de Soutien',
                    'quantity' => self::SUPPORT_QUANTITY,
                    'rarity' => 'common'
                ],
            ],
            'total_cards' => self::BRONZE_QUANTITY + self::SOLDIER_QUANTITY + self::GUARD_QUANTITY + self::SUPPORT_QUANTITY,
        ];
    }
}
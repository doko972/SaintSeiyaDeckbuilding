<?php

namespace App\Services;

use App\Models\Combo;
use Illuminate\Support\Collection;

class ComboService
{
    /**
     * Récupère tous les combos actifs formatés pour le battle state
     */
    public function getAllActiveCombos(): array
    {
        return Combo::where('is_active', true)
            ->with(['card1', 'card2', 'card3', 'leaderCard', 'attack'])
            ->get()
            ->map(fn($combo) => $combo->toArrayForBattle())
            ->toArray();
    }

    /**
     * Détecte les combos disponibles pour un ensemble de cartes sur le terrain
     *
     * @param array $fieldCards Les cartes sur le terrain (avec leurs card IDs)
     * @param array $allCombos Tous les combos actifs
     * @return array Les combos actifs sur ce terrain
     */
    public function detectAvailableCombos(array $fieldCards, array $allCombos): array
    {
        $fieldCardIds = array_map(fn($card) => $card['id'], $fieldCards);
        $availableCombos = [];

        foreach ($allCombos as $combo) {
            if ($this->isComboActiveOnField($combo, $fieldCardIds)) {
                $availableCombos[] = $combo;
            }
        }

        return $availableCombos;
    }

    /**
     * Vérifie si un combo spécifique est actif sur le terrain
     */
    public function isComboActiveOnField(array $combo, array $fieldCardIds): bool
    {
        $comboCardIds = $combo['card_ids'];
        $matchCount = 0;
        $usedFieldCards = [];

        // On doit trouver les 3 cartes distinctes du combo sur le terrain
        foreach ($comboCardIds as $comboCardId) {
            foreach ($fieldCardIds as $index => $fieldCardId) {
                if ($fieldCardId === $comboCardId && !in_array($index, $usedFieldCards)) {
                    $matchCount++;
                    $usedFieldCards[] = $index;
                    break;
                }
            }
        }

        return $matchCount >= 3;
    }

    /**
     * Trouve les combos qu'une carte spécifique peut lancer (en tant que leader)
     *
     * @param int $cardId L'ID de la carte
     * @param array $fieldCards Les cartes sur le terrain
     * @param array $allCombos Tous les combos actifs
     * @return array Les combos que cette carte peut lancer
     */
    public function getCombosForLeader(int $cardId, array $fieldCards, array $allCombos): array
    {
        $availableCombos = $this->detectAvailableCombos($fieldCards, $allCombos);

        return array_filter($availableCombos, function ($combo) use ($cardId) {
            return $combo['leader_card_id'] === $cardId;
        });
    }

    /**
     * Vérifie si un joueur peut utiliser une attaque combo
     *
     * @param array $combo Le combo à vérifier
     * @param array $attacker La carte attaquante
     * @param int $playerCosmos Le cosmos du joueur
     * @param array $fieldCards Les cartes sur le terrain
     * @return array ['can_use' => bool, 'reason' => string|null]
     */
    public function canUseCombo(array $combo, array $attacker, int $playerCosmos, array $fieldCards): array
    {
        // Vérifier que la carte est le leader
        if ($attacker['id'] !== $combo['leader_card_id']) {
            return ['can_use' => false, 'reason' => 'Cette carte n\'est pas le leader du combo'];
        }

        // Vérifier que le combo est actif sur le terrain
        $fieldCardIds = array_map(fn($card) => $card['id'], $fieldCards);
        if (!$this->isComboActiveOnField($combo, $fieldCardIds)) {
            return ['can_use' => false, 'reason' => 'Les 3 cartes du combo ne sont pas sur le terrain'];
        }

        // Vérifier l'endurance (coût du combo + coût de l'attaque)
        $totalEnduranceCost = $combo['endurance_cost'] + ($combo['attack']['endurance_cost'] ?? 0);
        $attackerEndurance = $attacker['current_endurance'] ?? 0;
        if ($attackerEndurance < $totalEnduranceCost) {
            return ['can_use' => false, 'reason' => 'Endurance insuffisante'];
        }

        // Vérifier le cosmos (coût du combo + coût de l'attaque)
        $totalCosmosCost = $combo['cosmos_cost'] + ($combo['attack']['cosmos_cost'] ?? 0);
        if ($playerCosmos < $totalCosmosCost) {
            return ['can_use' => false, 'reason' => 'Cosmos insuffisant'];
        }

        // Vérifier que la carte n'a pas déjà attaqué
        if ($attacker['has_attacked'] ?? false) {
            return ['can_use' => false, 'reason' => 'Cette carte a déjà attaqué ce tour'];
        }

        return ['can_use' => true, 'reason' => null];
    }

    /**
     * Calcule les coûts totaux d'une attaque combo
     */
    public function getComboTotalCosts(array $combo): array
    {
        return [
            'endurance' => $combo['endurance_cost'] + ($combo['attack']['endurance_cost'] ?? 0),
            'cosmos' => $combo['cosmos_cost'] + ($combo['attack']['cosmos_cost'] ?? 0),
        ];
    }

    /**
     * Applique les dégâts d'un combo
     */
    public function calculateComboDamage(array $combo, array $attacker, array $target): int
    {
        $baseDamage = $combo['attack']['damage'] ?? 0;
        $attackerPower = $attacker['power'] ?? 0;
        $targetDefense = $target['defense'] ?? 0;

        return max(0, $baseDamage + $attackerPower - $targetDefense);
    }
}

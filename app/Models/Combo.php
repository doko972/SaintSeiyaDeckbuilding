<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'card1_id',
        'card2_id',
        'card3_id',
        'leader_card_id',
        'attack_id',
        'endurance_cost',
        'cosmos_cost',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Première carte du combo
     */
    public function card1(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card1_id');
    }

    /**
     * Deuxième carte du combo
     */
    public function card2(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card2_id');
    }

    /**
     * Troisième carte du combo
     */
    public function card3(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card3_id');
    }

    /**
     * Carte leader (qui peut lancer l'attaque)
     */
    public function leaderCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'leader_card_id');
    }

    /**
     * Attaque spéciale du combo
     */
    public function attack(): BelongsTo
    {
        return $this->belongsTo(Attack::class);
    }

    /**
     * Retourne les IDs des 3 cartes du combo
     */
    public function getCardIds(): array
    {
        return [$this->card1_id, $this->card2_id, $this->card3_id];
    }

    /**
     * Vérifie si une carte donnée fait partie de ce combo
     */
    public function hasCard(int $cardId): bool
    {
        return in_array($cardId, $this->getCardIds());
    }

    /**
     * Vérifie si une carte est le leader de ce combo
     */
    public function isLeader(int $cardId): bool
    {
        return $this->leader_card_id === $cardId;
    }

    /**
     * Vérifie si les 3 cartes du combo sont présentes sur le terrain
     */
    public function isActiveOnField(array $fieldCardIds): bool
    {
        $comboCardIds = $this->getCardIds();

        // Compter combien de cartes du combo sont sur le terrain
        $matchCount = 0;
        $matchedComboCards = [];

        foreach ($fieldCardIds as $fieldCardId) {
            if (in_array($fieldCardId, $comboCardIds) && !in_array($fieldCardId, $matchedComboCards)) {
                $matchCount++;
                $matchedComboCards[] = $fieldCardId;
            }
        }

        return $matchCount >= 3;
    }

    /**
     * Détecte tous les combos disponibles pour un terrain donné
     */
    public static function findAvailableCombos(array $fieldCardIds): \Illuminate\Database\Eloquent\Collection
    {
        // Récupérer tous les combos actifs
        $activeCombos = self::where('is_active', true)
            ->with(['card1', 'card2', 'card3', 'leaderCard', 'attack'])
            ->get();

        // Filtrer ceux qui sont actifs sur le terrain
        return $activeCombos->filter(function ($combo) use ($fieldCardIds) {
            return $combo->isActiveOnField($fieldCardIds);
        });
    }

    /**
     * Format du combo pour le frontend (JSON)
     */
    public function toArrayForBattle(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'card_ids' => $this->getCardIds(),
            'leader_card_id' => $this->leader_card_id,
            'attack' => $this->attack ? [
                'id' => $this->attack->id,
                'name' => $this->attack->name,
                'damage' => $this->attack->damage,
                'description' => $this->attack->description,
                'effect_type' => $this->attack->effect_type,
                'effect_value' => $this->attack->effect_value,
            ] : null,
            'endurance_cost' => $this->endurance_cost,
            'cosmos_cost' => $this->cosmos_cost,
        ];
    }
}

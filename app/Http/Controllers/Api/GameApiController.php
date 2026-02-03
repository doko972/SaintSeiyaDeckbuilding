<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use App\Services\ComboService;
use App\Services\FusionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GameApiController extends Controller
{
    protected ComboService $comboService;
    protected FusionService $fusionService;

    public function __construct(ComboService $comboService, FusionService $fusionService)
    {
        $this->comboService = $comboService;
        $this->fusionService = $fusionService;
    }

    /**
     * Normaliser l'état du jeu (réindexer tous les tableaux)
     * Évite les problèmes de conversion JSON avec des clés non séquentielles
     * ET les problèmes de références PHP corrompues
     */
    private function normalizeState(array $state): array
    {
        // Reconstruire complètement le state pour éliminer TOUTE référence PHP
        $newState = [
            'turn' => $state['turn'] ?? 1,
            'phase' => $state['phase'] ?? 'player',
            'player' => [
                'cosmos' => $state['player']['cosmos'] ?? 5,
                'max_cosmos' => $state['player']['max_cosmos'] ?? 5,
                'health' => $state['player']['health'] ?? 30,
                'hand' => [],
                'field' => [],
                'deck' => [],
            ],
            'opponent' => [
                'cosmos' => $state['opponent']['cosmos'] ?? 5,
                'max_cosmos' => $state['opponent']['max_cosmos'] ?? 5,
                'health' => $state['opponent']['health'] ?? 30,
                'hand' => [],
                'field' => [],
                'deck' => [],
            ],
        ];

        // Reconstruire explicitement chaque carte du joueur
        foreach ($state['player']['hand'] ?? [] as $card) {
            $newState['player']['hand'][] = $this->cloneCard($card);
        }
        foreach ($state['player']['field'] ?? [] as $card) {
            $newState['player']['field'][] = $this->cloneCard($card);
        }
        foreach ($state['player']['deck'] ?? [] as $card) {
            $newState['player']['deck'][] = $this->cloneCard($card);
        }

        // Reconstruire explicitement chaque carte de l'adversaire
        foreach ($state['opponent']['hand'] ?? [] as $card) {
            $newState['opponent']['hand'][] = $this->cloneCard($card);
        }
        foreach ($state['opponent']['field'] ?? [] as $card) {
            $newState['opponent']['field'][] = $this->cloneCard($card);
        }
        foreach ($state['opponent']['deck'] ?? [] as $card) {
            $newState['opponent']['deck'][] = $this->cloneCard($card);
        }

        return $newState;
    }

    /**
     * Cloner une carte en copiant explicitement chaque propriété
     */
    private function cloneCard(array $card): array
    {
        return [
            'id' => $card['id'] ?? null,
            'instance_id' => $card['instance_id'] ?? uniqid('card_'),
            'name' => $card['name'] ?? 'Unknown',
            'cost' => $card['cost'] ?? 0,
            'health_points' => $card['health_points'] ?? 100,
            'max_hp' => $card['max_hp'] ?? 100,
            'current_hp' => $card['current_hp'] ?? 100,
            'endurance' => $card['endurance'] ?? 100,
            'current_endurance' => $card['current_endurance'] ?? 100,
            'defense' => $card['defense'] ?? 0,
            'power' => $card['power'] ?? 0,
            'cosmos' => $card['cosmos'] ?? 0,
            'rarity' => $card['rarity'] ?? 'common',
            'fusion_level' => $card['fusion_level'] ?? 1,
            'bonus_percent' => $card['bonus_percent'] ?? 0,
            'image' => $card['image'] ?? null,
            'has_attacked' => $card['has_attacked'] ?? false,
            'faction' => isset($card['faction']) ? [
                'name' => $card['faction']['name'] ?? null,
                'color_primary' => $card['faction']['color_primary'] ?? '#333',
                'color_secondary' => $card['faction']['color_secondary'] ?? '#555',
            ] : null,
            'main_attack' => isset($card['main_attack']) ? [
                'name' => $card['main_attack']['name'] ?? 'Attaque',
                'damage' => $card['main_attack']['damage'] ?? 50,
                'endurance_cost' => $card['main_attack']['endurance_cost'] ?? 20,
                'cosmos_cost' => $card['main_attack']['cosmos_cost'] ?? 0,
                'effect_type' => $card['main_attack']['effect_type'] ?? null,
                'effect_value' => $card['main_attack']['effect_value'] ?? null,
            ] : null,
            'secondary_attack_1' => isset($card['secondary_attack_1']) ? [
                'name' => $card['secondary_attack_1']['name'] ?? null,
                'damage' => $card['secondary_attack_1']['damage'] ?? 0,
                'endurance_cost' => $card['secondary_attack_1']['endurance_cost'] ?? 0,
                'cosmos_cost' => $card['secondary_attack_1']['cosmos_cost'] ?? 0,
            ] : null,
            'secondary_attack_2' => isset($card['secondary_attack_2']) ? [
                'name' => $card['secondary_attack_2']['name'] ?? null,
                'damage' => $card['secondary_attack_2']['damage'] ?? 0,
                'endurance_cost' => $card['secondary_attack_2']['endurance_cost'] ?? 0,
                'cosmos_cost' => $card['secondary_attack_2']['cosmos_cost'] ?? 0,
            ] : null,
        ];
    }

    /**
     * Initialiser un combat
     */
    public function initBattle(Request $request): JsonResponse
    {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
        ]);

        $deck = Deck::with('cards.faction', 'cards.mainAttack', 'cards.secondaryAttack1', 'cards.secondaryAttack2')
            ->findOrFail($request->deck_id);

        // Vérifier que le deck appartient à l'utilisateur
        if ($deck->user_id !== auth()->id()) {
            return response()->json(['message' => 'Deck non autorisé'], 403);
        }

        // Préparer les cartes du joueur (avec bonus de fusion)
        $playerDeck = $this->prepareDeck($deck->cards, auth()->user());
        shuffle($playerDeck);

        // Générer un deck ennemi (cartes aléatoires, sans bonus de fusion)
        $enemyCards = Card::with('faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2')
            ->inRandomOrder()
            ->limit(10)
            ->get();
        $enemyDeck = $this->prepareDeck($enemyCards);
        shuffle($enemyDeck);

        // Main initiale (5 cartes)
        $playerHand = array_splice($playerDeck, 0, min(5, count($playerDeck)));
        $enemyHand = array_splice($enemyDeck, 0, min(5, count($enemyDeck)));

        // État initial
        $battleState = [
            'turn' => 1,
            'phase' => 'player',
            'player' => [
                'deck' => $playerDeck,
                'hand' => $playerHand,
                'field' => [],
                'cosmos' => 5,
                'max_cosmos' => 5,
                'health' => 30,
            ],
            'opponent' => [
                'deck' => $enemyDeck,
                'hand' => $enemyHand,
                'field' => [],
                'cosmos' => 5,
                'max_cosmos' => 5,
                'health' => 30,
            ],
            // Charger tous les combos actifs pour le combat
            'all_combos' => $this->comboService->getAllActiveCombos(),
            // Tracker les combos utilisés (une seule utilisation par partie)
            'used_combos' => [],
        ];

        // IA joue des cartes au début
        $battleState = $this->aiPlayCards($battleState);

        return response()->json([
            'success' => true,
            'battle_state' => $this->normalizeState($battleState),
        ]);
    }

    /**
     * Jouer une carte de la main
     */
    public function playCard(Request $request): JsonResponse
    {
        $request->validate([
            'card_index' => 'required|integer|min:0',
            'battle_state' => 'required|array',
        ]);

        // IMPORTANT: Copie profonde immédiate pour éliminer toute référence PHP
        $state = json_decode(json_encode($request->battle_state), true);

        // Réindexer les tableaux pour éviter les problèmes de clés non séquentielles
        $state['player']['hand'] = array_values($state['player']['hand'] ?? []);
        $state['player']['field'] = array_values($state['player']['field'] ?? []);
        $state['opponent']['hand'] = array_values($state['opponent']['hand'] ?? []);
        $state['opponent']['field'] = array_values($state['opponent']['field'] ?? []);
        $cardIndex = $request->card_index;

        // Vérifier que la carte existe en main
        if (!isset($state['player']['hand'][$cardIndex])) {
            return response()->json(['message' => 'Carte non trouvée'], 400);
        }

        $card = $state['player']['hand'][$cardIndex];

        // Vérifier le coût
        if ($state['player']['cosmos'] < $card['cost']) {
            return response()->json(['message' => 'Cosmos insuffisant'], 400);
        }

        // Vérifier la limite du terrain (3 cartes max)
        if (count($state['player']['field']) >= 3) {
            return response()->json(['message' => 'Terrain plein (3 cartes max)'], 400);
        }

        // Jouer la carte
        $card['current_hp'] = $card['max_hp'];
        $card['current_endurance'] = $card['endurance'];
        $card['has_attacked'] = false;

        $state['player']['cosmos'] -= $card['cost'];
        $state['player']['field'][] = $card;
        array_splice($state['player']['hand'], $cardIndex, 1);

        return response()->json([
            'success' => true,
            'card_played' => $card['name'],
            'battle_state' => $this->normalizeState($state),
        ]);
    }

    /**
     * Attaquer une cible
     */
    public function attack(Request $request): JsonResponse
    {
        $request->validate([
            'attacker_index' => 'required|integer|min:0',
            'attack_type' => 'required|string',
            'target_index' => 'required|integer|min:0',
            'battle_state' => 'required|array',
        ]);

        // IMPORTANT: Copie profonde immédiate pour éliminer toute référence PHP
        $state = json_decode(json_encode($request->battle_state), true);

        // Réindexer les tableaux pour éviter les problèmes de clés non séquentielles
        $state['player']['hand'] = array_values($state['player']['hand'] ?? []);
        $state['player']['field'] = array_values($state['player']['field'] ?? []);
        $state['opponent']['hand'] = array_values($state['opponent']['hand'] ?? []);
        $state['opponent']['field'] = array_values($state['opponent']['field'] ?? []);

        $attackerIndex = $request->attacker_index;
        $targetIndex = $request->target_index;
        $attackType = $request->attack_type;

        // Vérifier l'attaquant
        if (!isset($state['player']['field'][$attackerIndex])) {
            return response()->json(['message' => 'Attaquant non trouvé'], 400);
        }

        // Vérifier la cible
        if (!isset($state['opponent']['field'][$targetIndex])) {
            return response()->json(['message' => 'Cible non trouvée'], 400);
        }

        // SANS références - accès direct par indices
        // Vérifier si a déjà attaqué
        if ($state['player']['field'][$attackerIndex]['has_attacked']) {
            return response()->json(['message' => 'Cette carte a déjà attaqué'], 400);
        }

        // Sauvegarder les infos nécessaires
        $attackerName = $state['player']['field'][$attackerIndex]['name'];
        $attackerPower = $state['player']['field'][$attackerIndex]['power'] ?? 0;
        $attackerEndurance = $state['player']['field'][$attackerIndex]['current_endurance'] ?? 0;

        $targetName = $state['opponent']['field'][$targetIndex]['name'];
        $targetDefense = $state['opponent']['field'][$targetIndex]['defense'] ?? 0;
        $targetCurrentHp = $state['opponent']['field'][$targetIndex]['current_hp'];

        // Déterminer l'attaque
        $isComboAttack = false;
        $comboName = null;
        $attacker = $state['player']['field'][$attackerIndex];

        // Vérifier si c'est une attaque combo (format: combo_X où X est l'ID du combo)
        if (str_starts_with($attackType, 'combo_')) {
            $comboId = (int) str_replace('combo_', '', $attackType);
            $allCombos = $state['all_combos'] ?? [];

            // Trouver le combo
            $combo = null;
            foreach ($allCombos as $c) {
                if ($c['id'] === $comboId) {
                    $combo = $c;
                    break;
                }
            }

            if (!$combo) {
                return response()->json(['message' => 'Combo non trouvé'], 400);
            }

            // Vérifier si le combo a déjà été utilisé dans cette partie
            $usedCombos = $state['used_combos'] ?? [];
            if (in_array($comboId, $usedCombos)) {
                return response()->json(['message' => 'Ce combo a déjà été utilisé dans cette partie'], 400);
            }

            // Vérifier que la carte est le leader
            if ($attacker['id'] !== $combo['leader_card_id']) {
                return response()->json(['message' => 'Cette carte n\'est pas le leader du combo'], 400);
            }

            // Vérifier que le combo est actif sur le terrain
            $canUseResult = $this->comboService->canUseCombo(
                $combo,
                $attacker,
                $state['player']['cosmos'] ?? 0,
                $state['player']['field']
            );

            if (!$canUseResult['can_use']) {
                return response()->json(['message' => $canUseResult['reason']], 400);
            }

            // Utiliser l'attaque du combo
            $attack = $combo['attack'];
            $isComboAttack = true;
            $comboName = $combo['name'];
            $usedComboId = $comboId; // Sauvegarder pour marquer comme utilisé

            // Ajouter les coûts supplémentaires du combo
            $attack['endurance_cost'] = ($attack['endurance_cost'] ?? 0) + ($combo['endurance_cost'] ?? 0);
            $attack['cosmos_cost'] = ($attack['cosmos_cost'] ?? 0) + ($combo['cosmos_cost'] ?? 0);
        } else {
            $attack = match ($attackType) {
                'main' => $state['player']['field'][$attackerIndex]['main_attack'] ?? null,
                'secondary1' => $state['player']['field'][$attackerIndex]['secondary_attack_1'] ?? null,
                'secondary2' => $state['player']['field'][$attackerIndex]['secondary_attack_2'] ?? null,
                default => null,
            };

            if (!$attack) {
                // Attaque basique
                $attack = [
                    'name' => 'Attaque',
                    'damage' => 50,
                    'endurance_cost' => 20,
                    'cosmos_cost' => 0,
                ];
            }
        }

        // Vérifier les coûts
        if ($attackerEndurance < $attack['endurance_cost']) {
            return response()->json(['message' => 'Endurance insuffisante'], 400);
        }

        if ($state['player']['cosmos'] < $attack['cosmos_cost']) {
            return response()->json(['message' => 'Cosmos insuffisant'], 400);
        }

        // Calculer les dégâts
        $damage = max(0, $attack['damage'] + $attackerPower - $targetDefense);
        $targetWillDie = ($targetCurrentHp - $damage) <= 0;

        // Appliquer les modifications via indices (PAS de références)
        $state['opponent']['field'][$targetIndex]['current_hp'] -= $damage;
        $state['player']['field'][$attackerIndex]['current_endurance'] -= $attack['endurance_cost'];
        $state['player']['cosmos'] -= $attack['cosmos_cost'];
        $state['player']['field'][$attackerIndex]['has_attacked'] = true;

        // Marquer le combo comme utilisé (une seule utilisation par partie)
        if ($isComboAttack && isset($usedComboId)) {
            if (!isset($state['used_combos'])) {
                $state['used_combos'] = [];
            }
            $state['used_combos'][] = $usedComboId;
        }

        $message = $isComboAttack
            ? "⚡ COMBO {$comboName} ! {$attackerName} utilise {$attack['name']} sur {$targetName} (-{$damage} PV)"
            : "{$attackerName} utilise {$attack['name']} sur {$targetName} (-{$damage} PV)";
        $battleEnded = false;
        $winner = null;

        // Vérifier si la cible est morte
        if ($targetWillDie) {
            array_splice($state['opponent']['field'], $targetIndex, 1);
            $message .= " - {$targetName} est vaincu !";

            // Vérifier victoire
            if (empty($state['opponent']['field']) && empty($state['opponent']['hand']) && empty($state['opponent']['deck'])) {
                $battleEnded = true;
                $winner = 'player';
            }
        }

        return response()->json([
            'success' => true,
            'damage' => $damage,
            'target_destroyed' => $targetWillDie,
            'target_name' => $targetName,
            'message' => $message,
            'battle_ended' => $battleEnded,
            'winner' => $winner,
            'battle_state' => $this->normalizeState($state),
        ]);
    }

    /**
     * Fin du tour
     */
    public function endTurn(Request $request): JsonResponse
    {
        $request->validate([
            'battle_state' => 'required|array',
        ]);

        // IMPORTANT: Copie profonde immédiate pour éliminer toute référence PHP
        $state = json_decode(json_encode($request->battle_state), true);

        // Réindexer les tableaux pour éviter les problèmes de clés non séquentielles
        $state['player']['hand'] = array_values($state['player']['hand'] ?? []);
        $state['player']['field'] = array_values($state['player']['field'] ?? []);
        $state['player']['deck'] = array_values($state['player']['deck'] ?? []);
        $state['opponent']['hand'] = array_values($state['opponent']['hand'] ?? []);
        $state['opponent']['field'] = array_values($state['opponent']['field'] ?? []);
        $state['opponent']['deck'] = array_values($state['opponent']['deck'] ?? []);

        $aiActions = [];
        $destroyedCards = [];

        // Tour de l'IA
        // 0. Reset has_attacked pour les cartes DÉJÀ sur le terrain (avant de jouer de nouvelles cartes)
        $opponentFieldCount = count($state['opponent']['field']);
        for ($i = 0; $i < $opponentFieldCount; $i++) {
            if (isset($state['opponent']['field'][$i])) {
                $state['opponent']['field'][$i]['has_attacked'] = false;
                // Régénération d'endurance
                $currentEndurance = $state['opponent']['field'][$i]['current_endurance'] ?? 0;
                $maxEndurance = $state['opponent']['field'][$i]['endurance'] ?? 100;
                $state['opponent']['field'][$i]['current_endurance'] = min($maxEndurance, $currentEndurance + 30);
            }
        }

        // 1. L'IA pioche
        if (!empty($state['opponent']['deck'])) {
            $drawnCard = array_shift($state['opponent']['deck']);
            $state['opponent']['hand'][] = $drawnCard;
            $aiActions[] = "L'adversaire pioche une carte";
        }

        // 2. L'IA gagne du cosmos
        $state['opponent']['max_cosmos'] = min(10, $state['opponent']['max_cosmos'] + 1);
        $state['opponent']['cosmos'] = $state['opponent']['max_cosmos'];

        // 3. L'IA joue des cartes (nouvelles cartes avec has_attacked = true, ne peuvent pas attaquer ce tour)
        $state = $this->aiPlayCards($state);
        // IMPORTANT: Copie profonde après aiPlayCards pour éliminer toute référence
        $state = json_decode(json_encode($state), true);
        if (count($state['opponent']['field']) > 0) {
            $aiActions[] = "L'adversaire déploie ses forces";
        }

        // 4. L'IA attaque
        $attackResults = $this->aiAttack($state);
        // IMPORTANT: Copie profonde après aiAttack pour éliminer toute référence
        $state = json_decode(json_encode($attackResults['state']), true);
        $aiActions = array_merge($aiActions, $attackResults['actions']);
        $destroyedCards = $attackResults['destroyed_cards'] ?? [];

        // Vérifier défaite du joueur
        $battleEnded = false;
        $winner = null;

        if (empty($state['player']['field']) && empty($state['player']['hand']) && empty($state['player']['deck'])) {
            $battleEnded = true;
            $winner = 'opponent';
        }

        // Nouveau tour du joueur
        $state['turn']++;

        // Joueur pioche
        if (!empty($state['player']['deck'])) {
            $drawnCard = array_shift($state['player']['deck']);
            $state['player']['hand'][] = $drawnCard;
        }

        // Joueur gagne du cosmos
        $state['player']['max_cosmos'] = min(10, $state['player']['max_cosmos'] + 1);
        $state['player']['cosmos'] = $state['player']['max_cosmos'];

        // Reset attaques et endurance des cartes du joueur (SANS références)
        $playerFieldCount = count($state['player']['field']);
        for ($i = 0; $i < $playerFieldCount; $i++) {
            if (isset($state['player']['field'][$i])) {
                $state['player']['field'][$i]['has_attacked'] = false;
                $currentEndurance = $state['player']['field'][$i]['current_endurance'] ?? 0;
                $maxEndurance = $state['player']['field'][$i]['endurance'] ?? 100;
                $state['player']['field'][$i]['current_endurance'] = min($maxEndurance, $currentEndurance + 30);
            }
        }

        return response()->json([
            'success' => true,
            'ai_actions' => $aiActions,
            'destroyed_cards' => $destroyedCards,
            'battle_ended' => $battleEnded,
            'winner' => $winner,
            'battle_state' => $this->normalizeState($state),
        ]);
    }

    /**
     * Réclamer la récompense
     */
    public function claimReward(Request $request): JsonResponse
    {
        $request->validate([
            'victory' => 'required|boolean',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $reward = $request->victory ? 100 : 25;
        $rankPromotion = null;

        $user->coins += $reward;

        if ($request->victory) {
            $user->wins++;
            $user->save();
            // Vérifier le changement de rang
            $rankPromotion = $user->checkAndUpdateRank();
        } else {
            $user->losses++;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'reward' => $reward,
            'new_balance' => $user->coins,
            'rank_promotion' => $rankPromotion,
        ]);
    }

    /**
     * Préparer le deck pour le combat
     * @param $cards Les cartes du deck
     * @param User|null $user L'utilisateur (pour appliquer les bonus de fusion)
     */
    private function prepareDeck($cards, ?User $user = null): array
    {
        $deck = [];

        foreach ($cards as $card) {
            $quantity = $card->pivot?->quantity ?? 1;

            // Récupérer le niveau de fusion si l'utilisateur est fourni
            $fusionLevel = 1;
            $bonusPercent = 0;
            if ($user) {
                $fusionLevel = $this->fusionService->getCardFusionLevel($user, $card->id);
                $bonusPercent = $this->fusionService->calculateCumulativeBonus($fusionLevel, $card->rarity);
            }

            // Calculer les stats boostées
            $multiplier = 1 + ($bonusPercent / 100);
            $boostedHp = (int) round($card->health_points * $multiplier);
            $boostedEndurance = (int) round($card->endurance * $multiplier);
            $boostedDefense = (int) round($card->defense * $multiplier);
            $boostedPower = (int) round($card->power * $multiplier);

            for ($i = 0; $i < $quantity; $i++) {
                $deck[] = [
                    'id' => $card->id,
                    'instance_id' => uniqid('card_' . $card->id . '_'),
                    'name' => $card->name,
                    'cost' => $card->cost,
                    'health_points' => $boostedHp,
                    'max_hp' => $boostedHp,
                    'current_hp' => $boostedHp,
                    'endurance' => $boostedEndurance,
                    'current_endurance' => $boostedEndurance,
                    'defense' => $boostedDefense,
                    'power' => $boostedPower,
                    'cosmos' => $card->cosmos, // Non affecté par la fusion
                    'rarity' => $card->rarity,
                    'fusion_level' => $fusionLevel,
                    'bonus_percent' => round($bonusPercent, 1),
                    'image' => $card->image_primary ? Storage::url($card->image_primary) : null,
                    'faction' => $card->faction ? [
                        'name' => $card->faction->name,
                        'color_primary' => $card->faction->color_primary,
                        'color_secondary' => $card->faction->color_secondary,
                    ] : null,
                    'main_attack' => $card->mainAttack ? [
                        'name' => $card->mainAttack->name,
                        'damage' => $card->mainAttack->damage,
                        'endurance_cost' => $card->mainAttack->endurance_cost,
                        'cosmos_cost' => $card->mainAttack->cosmos_cost,
                        'effect_type' => $card->mainAttack->effect_type,
                        'effect_value' => $card->mainAttack->effect_value,
                    ] : null,
                    'secondary_attack_1' => $card->secondaryAttack1 ? [
                        'name' => $card->secondaryAttack1->name,
                        'damage' => $card->secondaryAttack1->damage,
                        'endurance_cost' => $card->secondaryAttack1->endurance_cost,
                        'cosmos_cost' => $card->secondaryAttack1->cosmos_cost,
                    ] : null,
                    'secondary_attack_2' => $card->secondaryAttack2 ? [
                        'name' => $card->secondaryAttack2->name,
                        'damage' => $card->secondaryAttack2->damage,
                        'endurance_cost' => $card->secondaryAttack2->endurance_cost,
                        'cosmos_cost' => $card->secondaryAttack2->cosmos_cost,
                    ] : null,
                    'has_attacked' => false,
                ];
            }
        }

        return $deck;
    }

    /**
     * IA joue des cartes
     * IMPORTANT: N'utilise PAS de foreach ni de références pour éviter les bugs PHP de corruption
     */
    private function aiPlayCards(array $state): array
    {
        // L'IA joue des cartes tant qu'elle peut
        while (count($state['opponent']['field']) < 3 && !empty($state['opponent']['hand'])) {
            // Trouver une carte jouable - utiliser for au lieu de foreach
            $playableIndex = null;
            $handCount = count($state['opponent']['hand']);

            for ($idx = 0; $idx < $handCount; $idx++) {
                if (isset($state['opponent']['hand'][$idx])) {
                    $cardCost = $state['opponent']['hand'][$idx]['cost'] ?? 0;
                    if ($cardCost <= $state['opponent']['cosmos']) {
                        $playableIndex = $idx;
                        break;
                    }
                }
            }

            if ($playableIndex === null) {
                break;
            }

            // IMPORTANT: Copie profonde de la carte pour éviter toute référence PHP
            $cardToPlay = json_decode(json_encode($state['opponent']['hand'][$playableIndex]), true);
            $cardToPlay['current_hp'] = $cardToPlay['max_hp'];
            $cardToPlay['current_endurance'] = $cardToPlay['endurance'];
            $cardToPlay['has_attacked'] = true; // Ne peut pas attaquer le tour où elle est jouée

            $state['opponent']['cosmos'] -= $cardToPlay['cost'];
            $state['opponent']['field'][] = $cardToPlay;
            array_splice($state['opponent']['hand'], $playableIndex, 1);

            // Réindexer la main après suppression
            $state['opponent']['hand'] = array_values($state['opponent']['hand']);
        }

        return $state;
    }

    /**
     * IA attaque
     * IMPORTANT: Cette fonction n'utilise PAS de références pour éviter les bugs PHP
     */
    private function aiAttack(array $state): array
    {
        $actions = [];
        $destroyedCards = [];
        $attackedThisTurn = []; // Sécurité supplémentaire pour éviter les attaques multiples

        if (empty($state['player']['field'])) {
            return ['state' => $state, 'actions' => $actions, 'destroyed_cards' => $destroyedCards];
        }

        // Utiliser les indices au lieu des références pour éviter les corruptions
        $opponentFieldCount = count($state['opponent']['field']);

        for ($attackerIdx = 0; $attackerIdx < $opponentFieldCount; $attackerIdx++) {
            if (!isset($state['opponent']['field'][$attackerIdx])) {
                continue;
            }

            // Double vérification : flag has_attacked ET tableau local
            if ($state['opponent']['field'][$attackerIdx]['has_attacked'] || in_array($attackerIdx, $attackedThisTurn)) {
                continue;
            }

            if (empty($state['player']['field'])) {
                break;
            }

            // Choisir une cible (la plus faible) - SANS foreach pour éviter les bugs PHP
            $targetIndex = 0;
            $lowestHp = $state['player']['field'][0]['current_hp'] ?? PHP_INT_MAX;
            $playerFieldCount = count($state['player']['field']);

            for ($pIdx = 0; $pIdx < $playerFieldCount; $pIdx++) {
                if (isset($state['player']['field'][$pIdx])) {
                    $cardHp = $state['player']['field'][$pIdx]['current_hp'] ?? 0;
                    if ($cardHp < $lowestHp) {
                        $lowestHp = $cardHp;
                        $targetIndex = $pIdx;
                    }
                }
            }

            if (!isset($state['player']['field'][$targetIndex])) {
                continue;
            }

            // Sauvegarder les infos de la cible
            $targetName = $state['player']['field'][$targetIndex]['name'];
            $targetDefense = $state['player']['field'][$targetIndex]['defense'] ?? 0;
            $targetCurrentHp = $state['player']['field'][$targetIndex]['current_hp'];
            $targetInstanceId = $state['player']['field'][$targetIndex]['instance_id'] ?? null;

            // Choisir l'attaque (main ou attaque basique)
            $attack = $state['opponent']['field'][$attackerIdx]['main_attack'] ?? [
                'name' => 'Attaque',
                'damage' => 50,
                'endurance_cost' => 20,
                'cosmos_cost' => 0
            ];

            $attackerEndurance = $state['opponent']['field'][$attackerIdx]['current_endurance'] ?? 0;
            $attackerPower = $state['opponent']['field'][$attackerIdx]['power'] ?? 0;
            $attackerName = $state['opponent']['field'][$attackerIdx]['name'];

            // Vérifier l'endurance
            if ($attackerEndurance < $attack['endurance_cost']) {
                continue;
            }

            // Vérifier le cosmos
            if ($state['opponent']['cosmos'] < $attack['cosmos_cost']) {
                continue;
            }

            $damage = max(0, $attack['damage'] + $attackerPower - $targetDefense);
            $targetWillDie = ($targetCurrentHp - $damage) <= 0;

            // Appliquer les modifications via les indices (PAS de références)
            $state['player']['field'][$targetIndex]['current_hp'] -= $damage;
            $state['opponent']['field'][$attackerIdx]['current_endurance'] -= $attack['endurance_cost'];
            $state['opponent']['cosmos'] -= $attack['cosmos_cost'];
            $state['opponent']['field'][$attackerIdx]['has_attacked'] = true;
            $attackedThisTurn[] = $attackerIdx; // Tracker localement pour éviter les doubles attaques

            $actions[] = "{$attackerName} attaque {$targetName} (-{$damage} PV)";

            // Vérifier si cible morte
            if ($targetWillDie) {
                $destroyedCards[] = [
                    'name' => $targetName,
                    'instance_id' => $targetInstanceId,
                    'index' => $targetIndex,
                    'owner' => 'player'
                ];

                array_splice($state['player']['field'], $targetIndex, 1);
                $actions[] = "{$targetName} est vaincu !";
            }
        }

        // NOTE: Le reset de has_attacked se fait maintenant au DÉBUT du tour de l'IA dans endTurn()
        // Cela permet au frontend de savoir quelles cartes ont attaqué ce tour

        return ['state' => $state, 'actions' => $actions, 'destroyed_cards' => $destroyedCards];
    }
}
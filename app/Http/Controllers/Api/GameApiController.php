<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GameApiController extends Controller
{
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

        // Préparer les cartes du joueur
        $playerDeck = $this->prepareDeck($deck->cards);
        shuffle($playerDeck);

        // Générer un deck ennemi (cartes aléatoires)
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
                'cosmos' => 3,
                'max_cosmos' => 3,
                'health' => 30,
            ],
            'opponent' => [
                'deck' => $enemyDeck,
                'hand' => $enemyHand,
                'field' => [],
                'cosmos' => 3,
                'max_cosmos' => 3,
                'health' => 30,
            ],
        ];

        // IA joue des cartes au début
        $battleState = $this->aiPlayCards($battleState);

        return response()->json([
            'success' => true,
            'battle_state' => $battleState,
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

        $state = $request->battle_state;
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
            'battle_state' => $state,
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

        $state = $request->battle_state;
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

        $attacker = &$state['player']['field'][$attackerIndex];
        $target = &$state['opponent']['field'][$targetIndex];

        // Vérifier si a déjà attaqué
        if ($attacker['has_attacked']) {
            return response()->json(['message' => 'Cette carte a déjà attaqué'], 400);
        }

        // Déterminer l'attaque
        $attack = match($attackType) {
            'main' => $attacker['main_attack'] ?? null,
            'secondary1' => $attacker['secondary_attack_1'] ?? null,
            'secondary2' => $attacker['secondary_attack_2'] ?? null,
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

        // Vérifier les coûts
        if ($attacker['current_endurance'] < $attack['endurance_cost']) {
            return response()->json(['message' => 'Endurance insuffisante'], 400);
        }

        if ($state['player']['cosmos'] < $attack['cosmos_cost']) {
            return response()->json(['message' => 'Cosmos insuffisant'], 400);
        }

        // Calculer les dégâts
        $damage = max(0, $attack['damage'] + ($attacker['power'] ?? 0) - ($target['defense'] ?? 0));

        // Appliquer les dégâts
        $target['current_hp'] -= $damage;
        $attacker['current_endurance'] -= $attack['endurance_cost'];
        $state['player']['cosmos'] -= $attack['cosmos_cost'];
        $attacker['has_attacked'] = true;

        $message = "{$attacker['name']} utilise {$attack['name']} sur {$target['name']} (-{$damage} PV)";
        $battleEnded = false;
        $winner = null;

        // Vérifier si la cible est morte
        if ($target['current_hp'] <= 0) {
            array_splice($state['opponent']['field'], $targetIndex, 1);
            $message .= " - {$target['name']} est vaincu !";

            // Vérifier victoire
            if (empty($state['opponent']['field']) && empty($state['opponent']['hand']) && empty($state['opponent']['deck'])) {
                $battleEnded = true;
                $winner = 'player';
            }
        }

        return response()->json([
            'success' => true,
            'damage' => $damage,
            'message' => $message,
            'battle_ended' => $battleEnded,
            'winner' => $winner,
            'battle_state' => $state,
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

        $state = $request->battle_state;
        $aiActions = [];

        // Tour de l'IA
        // 1. L'IA pioche
        if (!empty($state['opponent']['deck'])) {
            $drawnCard = array_shift($state['opponent']['deck']);
            $state['opponent']['hand'][] = $drawnCard;
            $aiActions[] = "L'adversaire pioche une carte";
        }

        // 2. L'IA gagne du cosmos
        $state['opponent']['max_cosmos'] = min(10, $state['opponent']['max_cosmos'] + 1);
        $state['opponent']['cosmos'] = $state['opponent']['max_cosmos'];

        // 3. L'IA joue des cartes
        $state = $this->aiPlayCards($state);
        if (count($state['opponent']['field']) > 0) {
            $aiActions[] = "L'adversaire déploie ses forces";
        }

        // 4. L'IA attaque
        $attackResults = $this->aiAttack($state);
        $state = $attackResults['state'];
        $aiActions = array_merge($aiActions, $attackResults['actions']);

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

        // Reset attaques et endurance des cartes du joueur
        foreach ($state['player']['field'] as &$card) {
            $card['has_attacked'] = false;
            $card['current_endurance'] = min($card['endurance'], $card['current_endurance'] + 30);
        }

        return response()->json([
            'success' => true,
            'ai_actions' => $aiActions,
            'battle_ended' => $battleEnded,
            'winner' => $winner,
            'battle_state' => $state,
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

        $user = auth()->user();
        $reward = $request->victory ? 100 : 25;

        $user->coins += $reward;
        
        if ($request->victory) {
            $user->wins++;
        } else {
            $user->losses++;
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'reward' => $reward,
            'new_balance' => $user->coins,
        ]);
    }

    /**
     * Préparer le deck pour le combat
     */
    private function prepareDeck($cards): array
    {
        $deck = [];
        
        foreach ($cards as $card) {
            $quantity = $card->pivot->quantity ?? 1;
            
            for ($i = 0; $i < $quantity; $i++) {
                $deck[] = [
                    'id' => $card->id,
                    'name' => $card->name,
                    'cost' => $card->cost,
                    'health_points' => $card->health_points,
                    'max_hp' => $card->health_points,
                    'current_hp' => $card->health_points,
                    'endurance' => $card->endurance,
                    'current_endurance' => $card->endurance,
                    'defense' => $card->defense,
                    'power' => $card->power,
                    'cosmos' => $card->cosmos,
                    'rarity' => $card->rarity,
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
     */
    private function aiPlayCards(array $state): array
    {
        // L'IA joue des cartes tant qu'elle peut
        while (count($state['opponent']['field']) < 3 && !empty($state['opponent']['hand'])) {
            // Trouver une carte jouable
            $playableIndex = null;
            foreach ($state['opponent']['hand'] as $index => $card) {
                if ($card['cost'] <= $state['opponent']['cosmos']) {
                    $playableIndex = $index;
                    break;
                }
            }

            if ($playableIndex === null) break;

            $card = $state['opponent']['hand'][$playableIndex];
            $card['current_hp'] = $card['max_hp'];
            $card['current_endurance'] = $card['endurance'];
            $card['has_attacked'] = true; // Ne peut pas attaquer le tour où elle est jouée

            $state['opponent']['cosmos'] -= $card['cost'];
            $state['opponent']['field'][] = $card;
            array_splice($state['opponent']['hand'], $playableIndex, 1);
        }

        return $state;
    }

    /**
     * IA attaque
     * 🔧 CORRIGÉ : L'IA doit maintenant payer le cosmos pour attaquer !
     */
    private function aiAttack(array $state): array
    {
        $actions = [];

        if (empty($state['player']['field'])) {
            return ['state' => $state, 'actions' => $actions];
        }

        foreach ($state['opponent']['field'] as &$attacker) {
            if ($attacker['has_attacked']) continue;
            if (empty($state['player']['field'])) break;

            // Choisir une cible (la plus faible)
            $targetIndex = 0;
            $lowestHp = $state['player']['field'][0]['current_hp'];
            
            foreach ($state['player']['field'] as $i => $target) {
                if ($target['current_hp'] < $lowestHp) {
                    $lowestHp = $target['current_hp'];
                    $targetIndex = $i;
                }
            }

            $target = &$state['player']['field'][$targetIndex];
            
            // Choisir l'attaque (main ou attaque basique)
            $attack = $attacker['main_attack'] ?? [
                'name' => 'Attaque', 
                'damage' => 50, 
                'endurance_cost' => 20, 
                'cosmos_cost' => 0
            ];
            
            // ✅ Vérifier l'endurance
            if ($attacker['current_endurance'] < $attack['endurance_cost']) {
                continue;
            }

            // ✅ FIX DU BUG : Vérifier le cosmos !
            if ($state['opponent']['cosmos'] < $attack['cosmos_cost']) {
                continue; // Pas assez de cosmos, skip cette attaque
            }

            $damage = max(0, $attack['damage'] + ($attacker['power'] ?? 0) - ($target['defense'] ?? 0));
            
            $target['current_hp'] -= $damage;
            $attacker['current_endurance'] -= $attack['endurance_cost'];
            $state['opponent']['cosmos'] -= $attack['cosmos_cost']; // ✅ L'IA PAYE maintenant !
            $attacker['has_attacked'] = true;

            $actions[] = "{$attacker['name']} attaque {$target['name']} (-{$damage} PV)";

            // Vérifier si cible morte
            if ($target['current_hp'] <= 0) {
                array_splice($state['player']['field'], $targetIndex, 1);
                $actions[] = "{$target['name']} est vaincu !";
            }
        }

        // Reset pour prochain tour
        foreach ($state['opponent']['field'] as &$card) {
            $card['has_attacked'] = false;
            $card['current_endurance'] = min($card['endurance'], $card['current_endurance'] + 30);
        }

        return ['state' => $state, 'actions' => $actions];
    }
}
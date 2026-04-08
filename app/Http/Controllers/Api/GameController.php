<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Initialise une nouvelle partie
     */
    public function initBattle(Request $request): JsonResponse
    {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
            'opponent_type' => 'required|in:ai,player',
        ]);

        $deck = Deck::with(['cards.faction', 'cards.mainAttack', 'cards.secondaryAttack1', 'cards.secondaryAttack2', 'cards.cardImages'])
            ->findOrFail($request->deck_id);

        // Vérifier que le deck appartient à l'utilisateur
        if ($deck->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ce deck ne vous appartient pas.',
            ], 403);
        }

        // Vérifier que le deck a des cartes
        if ($deck->cards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre deck est vide.',
            ], 400);
        }

        // Préparer les cartes du joueur (avec leurs stats de combat)
        $playerCards = $this->prepareCardsForBattle($deck->cards, $request->user()->id);

        // Générer l'adversaire IA
        $opponentCards = $this->generateAIOpponent(count($playerCards));

        // Créer l'état initial du combat
        $battleState = [
            'id' => uniqid('battle_'),
            'turn' => 1,
            'current_player' => 'player',
            'phase' => 'draw', // draw, main, attack, end
            'player' => [
                'hand' => array_slice($playerCards, 0, 5),
                'deck' => array_slice($playerCards, 5),
                'field' => [],
                'graveyard' => [],
                'cosmos_pool' => 3,
                'max_cosmos' => 10,
            ],
            'opponent' => [
                'hand' => array_slice($opponentCards, 0, 5),
                'deck' => array_slice($opponentCards, 5),
                'field' => [],
                'graveyard' => [],
                'cosmos_pool' => 3,
                'max_cosmos' => 10,
            ],
            'log' => [
                ['turn' => 0, 'message' => 'Le combat commence !'],
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $battleState,
        ]);
    }

    /**
     * Jouer une carte sur le terrain
     */
    public function playCard(Request $request): JsonResponse
    {
        $request->validate([
            'battle_state' => 'required|array',
            'card_index' => 'required|integer|min:0',
        ]);

        $state = $request->battle_state;
        $cardIndex = $request->card_index;

        // Vérifier que c'est le tour du joueur
        if ($state['current_player'] !== 'player') {
            return response()->json([
                'success' => false,
                'message' => 'Ce n\'est pas votre tour.',
            ], 400);
        }

        // Vérifier que la carte existe dans la main
        if (!isset($state['player']['hand'][$cardIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'Carte invalide.',
            ], 400);
        }

        $card = $state['player']['hand'][$cardIndex];

        // Vérifier le coût en cosmos
        if ($state['player']['cosmos_pool'] < $card['stats']['cost']) {
            return response()->json([
                'success' => false,
                'message' => 'Pas assez de Cosmos pour jouer cette carte.',
            ], 400);
        }

        // Vérifier la limite du terrain (max 5 cartes)
        if (count($state['player']['field']) >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Votre terrain est plein (max 5 cartes).',
            ], 400);
        }

        // Jouer la carte
        $state['player']['cosmos_pool'] -= $card['stats']['cost'];
        $state['player']['field'][] = $card;
        array_splice($state['player']['hand'], $cardIndex, 1);

        // Log
        $state['log'][] = [
            'turn' => $state['turn'],
            'message' => "Vous invoquez {$card['name']} !",
        ];

        return response()->json([
            'success' => true,
            'data' => $state,
        ]);
    }

    /**
     * Effectuer une attaque
     */
    public function attack(Request $request): JsonResponse
    {
        $request->validate([
            'battle_state' => 'required|array',
            'attacker_index' => 'required|integer|min:0',
            'attack_type' => 'required|in:main,secondary_1,secondary_2',
            'target_index' => 'required|integer|min:0',
        ]);

        $state = $request->battle_state;
        $attackerIndex = $request->attacker_index;
        $attackType = $request->attack_type;
        $targetIndex = $request->target_index;

        // Vérifier que c'est le tour du joueur
        if ($state['current_player'] !== 'player') {
            return response()->json([
                'success' => false,
                'message' => 'Ce n\'est pas votre tour.',
            ], 400);
        }

        // Vérifier l'attaquant
        if (!isset($state['player']['field'][$attackerIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'Attaquant invalide.',
            ], 400);
        }

        // Vérifier la cible
        if (!isset($state['opponent']['field'][$targetIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'Cible invalide.',
            ], 400);
        }

        $attacker = &$state['player']['field'][$attackerIndex];
        $target = &$state['opponent']['field'][$targetIndex];

        // Vérifier si l'attaquant a déjà attaqué ce tour
        if ($attacker['has_attacked'] ?? false) {
            return response()->json([
                'success' => false,
                'message' => 'Cette carte a déjà attaqué ce tour.',
            ], 400);
        }

        // Récupérer l'attaque
        $attack = $attacker['attacks'][$attackType] ?? null;
        if (!$attack) {
            return response()->json([
                'success' => false,
                'message' => 'Attaque invalide.',
            ], 400);
        }

        // Vérifier les coûts
        if ($attacker['current_endurance'] < $attack['endurance_cost']) {
            return response()->json([
                'success' => false,
                'message' => 'Pas assez d\'endurance.',
            ], 400);
        }

        if ($state['player']['cosmos_pool'] < $attack['cosmos_cost']) {
            return response()->json([
                'success' => false,
                'message' => 'Pas assez de Cosmos.',
            ], 400);
        }

        // Calculer les dégâts
        $baseDamage = $attack['damage'];
        $powerBonus = $attacker['stats']['power'] / 10;
        $defenseReduction = $target['stats']['defense'] / 2;
        $finalDamage = max(1, round(($baseDamage + $powerBonus) - $defenseReduction));

        // Appliquer les dégâts
        $attacker['current_endurance'] -= $attack['endurance_cost'];
        $state['player']['cosmos_pool'] -= $attack['cosmos_cost'];
        $target['current_hp'] -= $finalDamage;
        $attacker['has_attacked'] = true;

        // Sauvegarder les noms avant de libérer les références
        $attackerName = $attacker['name'];
        $targetName = $target['name'];
        $attackName = $attack['name'];
        $targetCurrentHp = $target['current_hp'];

        // Log
        $state['log'][] = [
            'turn' => $state['turn'],
            'message' => "{$attackerName} utilise {$attackName} sur {$targetName} et inflige {$finalDamage} dégâts !",
        ];

        // Appliquer les effets
        $state = $this->applyEffect($state, $attack, $attacker, $target, 'opponent', $targetIndex);

        // IMPORTANT: libérer les références AVANT de modifier le tableau
        unset($attacker);
        unset($target);

        // Vérifier si la cible est KO
        if ($targetCurrentHp <= 0) {
            $state['opponent']['graveyard'][] = $state['opponent']['field'][$targetIndex];
            array_splice($state['opponent']['field'], $targetIndex, 1);
            $state['log'][] = [
                'turn' => $state['turn'],
                'message' => "{$targetName} est KO !",
            ];
        }

        // Vérifier la victoire
        $state = $this->checkVictory($state);

        return response()->json([
            'success' => true,
            'data' => $state,
        ]);
    }

    /**
     * Terminer le tour
     */
    public function endTurn(Request $request): JsonResponse
    {
        $request->validate([
            'battle_state' => 'required|array',
        ]);

        $state = $request->battle_state;

        // Réinitialiser les états d'attaque des cartes du joueur
        foreach ($state['player']['field'] as &$card) {
            $card['has_attacked'] = false;
            // Régénérer un peu d'endurance
            $card['current_endurance'] = min(
                $card['stats']['endurance'],
                $card['current_endurance'] + 10
            );
        }
        unset($card); // IMPORTANT: libérer la référence pour éviter le bug de dédoublement

        // Tour de l'IA
        $state = $this->playAITurn($state);

        // Nouveau tour
        $state['turn']++;
        $state['current_player'] = 'player';

        // Augmenter le cosmos max (jusqu'à 10)
        if ($state['player']['max_cosmos'] < 10) {
            $state['player']['max_cosmos']++;
        }
        $state['player']['cosmos_pool'] = $state['player']['max_cosmos'];

        // Piocher une carte
        if (!empty($state['player']['deck'])) {
            $state['player']['hand'][] = array_shift($state['player']['deck']);
        }

        // Réinitialiser les états d'attaque
        foreach ($state['player']['field'] as &$card) {
            $card['has_attacked'] = false;
        }
        unset($card); // IMPORTANT: libérer la référence pour éviter le bug de dédoublement

        $state['log'][] = [
            'turn' => $state['turn'],
            'message' => "Tour {$state['turn']} - C'est à vous de jouer !",
        ];

        // Vérifier la victoire
        $state = $this->checkVictory($state);

        return response()->json([
            'success' => true,
            'data' => $state,
        ]);
    }

    /**
     * Prépare les cartes pour le combat
     */
    private function prepareCardsForBattle($cards, ?int $userId = null): array
    {
        $battleCards = [];

        // Récupérer les niveaux de fusion du joueur en une seule requête
        $fusionLevels = [];
        if ($userId) {
            $fusionLevels = \DB::table('user_cards')
                ->where('user_id', $userId)
                ->pluck('fusion_level', 'card_id')
                ->toArray();
        }

        foreach ($cards as $card) {
            $fusionLevel = $fusionLevels[$card->id] ?? 1;
            $levelImage  = $card->imageForLevel($fusionLevel);
            $imagePath   = $levelImage?->image_primary ?? $card->image_primary;

            for ($i = 0; $i < $card->pivot->quantity; $i++) {
                $battleCards[] = [
                    'id' => $card->id,
                    'name' => $card->name,
                    'grade' => $card->grade,
                    'element' => $card->element,
                    'rarity' => $card->rarity,
                    'stats' => [
                        'health_points' => $card->health_points,
                        'endurance' => $card->endurance,
                        'defense' => $card->defense,
                        'power' => $card->power,
                        'cosmos' => $card->cosmos,
                        'cost' => $card->cost,
                    ],
                    'current_hp' => $card->health_points,
                    'current_endurance' => $card->endurance,
                    'faction' => [
                        'name' => $card->faction->name,
                        'color' => $card->faction->color_primary,
                    ],
                    'attacks' => [
                        'main' => [
                            'name' => $card->mainAttack->name,
                            'damage' => $card->mainAttack->damage,
                            'endurance_cost' => $card->mainAttack->endurance_cost,
                            'cosmos_cost' => $card->mainAttack->cosmos_cost,
                            'effect_type' => $card->mainAttack->effect_type,
                            'effect_value' => $card->mainAttack->effect_value,
                        ],
                        'secondary_1' => $card->secondaryAttack1 ? [
                            'name' => $card->secondaryAttack1->name,
                            'damage' => $card->secondaryAttack1->damage,
                            'endurance_cost' => $card->secondaryAttack1->endurance_cost,
                            'cosmos_cost' => $card->secondaryAttack1->cosmos_cost,
                            'effect_type' => $card->secondaryAttack1->effect_type,
                            'effect_value' => $card->secondaryAttack1->effect_value,
                        ] : null,
                        'secondary_2' => $card->secondaryAttack2 ? [
                            'name' => $card->secondaryAttack2->name,
                            'damage' => $card->secondaryAttack2->damage,
                            'endurance_cost' => $card->secondaryAttack2->endurance_cost,
                            'cosmos_cost' => $card->secondaryAttack2->cosmos_cost,
                            'effect_type' => $card->secondaryAttack2->effect_type,
                            'effect_value' => $card->secondaryAttack2->effect_value,
                        ] : null,
                    ],
                    'passive' => [
                        'name' => $card->passive_ability_name,
                        'description' => $card->passive_ability_description,
                    ],
                    'image' => $imagePath ? asset('storage/' . $imagePath) : null,
                    'has_attacked' => false,
                    'status_effects' => [],
                ];
            }
        }

        shuffle($battleCards);
        return $battleCards;
    }

    /**
     * Génère un adversaire IA
     */
    private function generateAIOpponent(int $cardCount): array
    {
        $aiCards = Card::with(['faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2'])
            ->inRandomOrder()
            ->limit(max($cardCount, 10))
            ->get();

        return $this->prepareCardsForBattle($aiCards->map(function ($card) {
            $card->pivot = (object) ['quantity' => 1];
            return $card;
        }));
    }

    /**
     * Applique les effets d'une attaque
     */
    private function applyEffect(array $state, array $attack, array &$attacker, array &$target, string $targetSide, int $targetIndex): array
    {
        if ($attack['effect_type'] === 'none') {
            return $state;
        }

        switch ($attack['effect_type']) {
            case 'burn':
                $target['status_effects'][] = [
                    'type' => 'burn',
                    'value' => $attack['effect_value'],
                    'duration' => 3,
                ];
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$target['name']} est en feu ! (Brûlure: {$attack['effect_value']}/tour)",
                ];
                break;

            case 'freeze':
                $target['status_effects'][] = [
                    'type' => 'freeze',
                    'duration' => $attack['effect_value'],
                ];
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$target['name']} est gelé pendant {$attack['effect_value']} tour(s) !",
                ];
                break;

            case 'stun':
                $target['status_effects'][] = [
                    'type' => 'stun',
                    'duration' => $attack['effect_value'],
                ];
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$target['name']} est étourdi !",
                ];
                break;

            case 'heal':
                $healAmount = $attack['effect_value'];
                $attacker['current_hp'] = min(
                    $attacker['stats']['health_points'],
                    $attacker['current_hp'] + $healAmount
                );
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$attacker['name']} récupère {$healAmount} PV !",
                ];
                break;

            case 'drain':
                $drainAmount = $attack['effect_value'];
                $attacker['current_hp'] = min(
                    $attacker['stats']['health_points'],
                    $attacker['current_hp'] + $drainAmount
                );
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$attacker['name']} absorbe {$drainAmount} PV !",
                ];
                break;

            case 'buff_attack':
                $attacker['stats']['power'] += $attack['effect_value'];
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$attacker['name']} gagne +{$attack['effect_value']} en puissance !",
                ];
                break;

            case 'buff_defense':
                $attacker['stats']['defense'] += $attack['effect_value'];
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$attacker['name']} gagne +{$attack['effect_value']} en défense !",
                ];
                break;

            case 'debuff':
                $target['stats']['power'] = max(0, $target['stats']['power'] - $attack['effect_value']);
                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "{$target['name']} perd {$attack['effect_value']} en puissance !",
                ];
                break;
        }

        return $state;
    }

    /**
     * Tour de l'IA
     */
    private function playAITurn(array $state): array
    {
        $state['log'][] = [
            'turn' => $state['turn'],
            'message' => "Tour de l'adversaire...",
        ];

        // Augmenter le cosmos de l'IA
        if ($state['opponent']['max_cosmos'] < 10) {
            $state['opponent']['max_cosmos']++;
        }
        $state['opponent']['cosmos_pool'] = $state['opponent']['max_cosmos'];

        // Piocher une carte
        if (!empty($state['opponent']['deck'])) {
            $state['opponent']['hand'][] = array_shift($state['opponent']['deck']);
        }

        // L'IA joue des cartes si possible
        foreach ($state['opponent']['hand'] as $index => $card) {
            if (count($state['opponent']['field']) >= 3)
                break;

            if ($state['opponent']['cosmos_pool'] >= $card['stats']['cost']) {
                $state['opponent']['cosmos_pool'] -= $card['stats']['cost'];
                $state['opponent']['field'][] = $card;
                array_splice($state['opponent']['hand'], $index, 1);

                $state['log'][] = [
                    'turn' => $state['turn'],
                    'message' => "L'adversaire invoque {$card['name']} !",
                ];
                break; // L'IA ne joue qu'une carte par tour
            }
        }

        // L'IA attaque si possible
        if (!empty($state['opponent']['field']) && !empty($state['player']['field'])) {
            foreach ($state['opponent']['field'] as &$aiCard) {
                if ($aiCard['has_attacked'] ?? false)
                    continue;
                if (empty($state['player']['field']))
                    break;

                // Choisir une cible aléatoire
                $targetIndex = array_rand($state['player']['field']);
                $target = &$state['player']['field'][$targetIndex];

                // Utiliser l'attaque principale
                $attack = $aiCard['attacks']['main'];

                if (
                    $aiCard['current_endurance'] >= $attack['endurance_cost'] &&
                    $state['opponent']['cosmos_pool'] >= $attack['cosmos_cost']
                ) {

                    // Calculer les dégâts
                    $baseDamage = $attack['damage'];
                    $powerBonus = $aiCard['stats']['power'] / 10;
                    $defenseReduction = $target['stats']['defense'] / 2;
                    $finalDamage = max(1, round(($baseDamage + $powerBonus) - $defenseReduction));

                    // Appliquer
                    $aiCard['current_endurance'] -= $attack['endurance_cost'];
                    $state['opponent']['cosmos_pool'] -= $attack['cosmos_cost'];
                    $target['current_hp'] -= $finalDamage;
                    $aiCard['has_attacked'] = true;

                    $state['log'][] = [
                        'turn' => $state['turn'],
                        'message' => "{$aiCard['name']} attaque {$target['name']} avec {$attack['name']} et inflige {$finalDamage} dégâts !",
                    ];

                    // Vérifier si la cible est KO
                    if ($target['current_hp'] <= 0) {
                        $state['player']['graveyard'][] = $target;
                        // IMPORTANT: libérer la référence AVANT array_splice
                        $targetName = $target['name'];
                        unset($target);
                        array_splice($state['player']['field'], $targetIndex, 1);
                        $state['log'][] = [
                            'turn' => $state['turn'],
                            'message' => "{$targetName} est KO !",
                        ];
                    } else {
                        unset($target); // Libérer la référence même si pas KO
                    }
                }
            }
            unset($aiCard); // IMPORTANT: libérer la référence de la boucle
        }

        // Réinitialiser les états d'attaque de l'IA
        foreach ($state['opponent']['field'] as &$card) {
            $card['has_attacked'] = false;
            $card['current_endurance'] = min(
                $card['stats']['endurance'],
                $card['current_endurance'] + 10
            );
        }
        unset($card); // IMPORTANT: libérer la référence pour éviter le bug de dédoublement

        return $state;
    }

    /**
     * Vérifie les conditions de victoire
     */
    private function checkVictory(array $state): array
    {
        // Victoire si l'adversaire n'a plus de cartes
        if (empty($state['opponent']['field']) && empty($state['opponent']['hand']) && empty($state['opponent']['deck'])) {
            $state['game_over'] = true;
            $state['winner'] = 'player';
            $state['log'][] = [
                'turn' => $state['turn'],
                'message' => '🎉 VICTOIRE ! Vous avez triomphé',
            ];
        }

        // Défaite si le joueur n'a plus de cartes
        if (empty($state['player']['field']) && empty($state['player']['hand']) && empty($state['player']['deck'])) {
            $state['game_over'] = true;
            $state['winner'] = 'opponent';
            $state['log'][] = [
                'turn' => $state['turn'],
                'message' => '💀 DÉFAITE...',
            ];
        }

        return $state;
    }
    /**
     * Réclamer la récompense de fin de combat
     */
    public function claimReward(Request $request): JsonResponse
    {
        $request->validate([
            'winner' => 'required|in:player,opponent',
        ]);

        $user = $request->user();
        $isVictory = $request->winner === 'player';
        $rankPromotion = null;

        if ($isVictory) {
            $rankPromotion = $user->recordWin(300);
            $message = 'Victoire ! Vous gagnez 300 pièces !';
            // Mission journalière : gagner 1 combat
            app(\App\Services\DailyMissionService::class)->complete($user, 'combat_win');
            // Succès
            app(\App\Services\AchievementService::class)->checkAndUnlock($user);
        } else {
            $user->recordLoss(100);
            $message = 'Défaite... Vous gagnez quand même 100 pièces.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'coins' => $user->coins,
            'wins' => $user->wins,
            'losses' => $user->losses,
            'rank_promotion' => $rankPromotion,
        ]);
    }
}
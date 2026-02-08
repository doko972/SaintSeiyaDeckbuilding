<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\TournamentMatch;
use App\Services\ComboService;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PvpApiController extends Controller
{
    protected ComboService $comboService;
    protected TournamentService $tournamentService;

    public function __construct(ComboService $comboService, TournamentService $tournamentService)
    {
        $this->comboService = $comboService;
        $this->tournamentService = $tournamentService;
    }

    public function getWaitingBattles(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $waitingBattles = Battle::where('status', 'waiting')
            ->where('player1_id', '!=', $user->id)
            ->with(['player1:id,name', 'player1Deck:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($battle) {
                return [
                    'id' => $battle->id,
                    'player1_name' => $battle->player1->name,
                    'player1_initial' => strtoupper(substr($battle->player1->name, 0, 1)),
                    'deck_name' => $battle->player1Deck->name,
                    'created_at' => $battle->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'battles' => $waitingBattles,
        ]);
    }

    public function getBattleState(Battle $battle): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        if ($battle->player1_id !== $user->id && $battle->player2_id !== $user->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        return response()->json([
            'battle_state' => $battle->battle_state,
            'status' => $battle->status,
            'current_turn_user_id' => $battle->current_turn_user_id,
            'is_my_turn' => $battle->current_turn_user_id === $user->id,
            'turn_number' => $battle->turn_number,
            'winner_id' => $battle->winner_id,
        ]);
    }

    public function playCard(Request $request): JsonResponse
    {
        $request->validate([
            'battle_id' => 'required|exists:battles,id',
            'card_index' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $battle = Battle::findOrFail($request->battle_id);

        // Vérifier que l'utilisateur participe à ce combat
        if ($battle->player1_id !== $user->id && $battle->player2_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Vous ne participez pas à ce combat'], 403);
        }

        if (!$battle->isPlayerTurn($user)) {
            return response()->json(['success' => false, 'message' => 'Ce n\'est pas votre tour'], 400);
        }

        $playerKey = $battle->player1_id === $user->id ? 'player1' : 'player2';
        $state = $battle->battle_state;

        $cardIndex = $request->card_index;

        if (!isset($state[$playerKey]['hand'][$cardIndex])) {
            return response()->json(['success' => false, 'message' => 'Carte non trouvée'], 400);
        }

        $card = $state[$playerKey]['hand'][$cardIndex];

        if ($state[$playerKey]['cosmos'] < $card['cost']) {
            return response()->json(['success' => false, 'message' => 'Cosmos insuffisant'], 400);
        }

        if (count($state[$playerKey]['field']) >= 5) {
            return response()->json(['success' => false, 'message' => 'Terrain plein'], 400);
        }

        // Jouer la carte
        $card['current_hp'] = $card['max_hp'];
        $card['current_endurance'] = $card['endurance'];
        $card['has_attacked'] = false;

        $state[$playerKey]['cosmos'] -= $card['cost'];
        $state[$playerKey]['field'][] = $card;
        array_splice($state[$playerKey]['hand'], $cardIndex, 1);

        $battle->update([
            'battle_state' => $state,
            'last_action_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'card_played' => $card['name'],
            'battle_state' => $state,
        ]);
    }

    public function attack(Request $request): JsonResponse
    {
        $request->validate([
            'battle_id' => 'required|exists:battles,id',
            'attacker_index' => 'required|integer|min:0',
            'attack_type' => 'required|string',
            'target_index' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $battle = Battle::findOrFail($request->battle_id);

        // Vérifier que l'utilisateur participe à ce combat
        if ($battle->player1_id !== $user->id && $battle->player2_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Vous ne participez pas à ce combat'], 403);
        }

        if (!$battle->isPlayerTurn($user)) {
            return response()->json(['success' => false, 'message' => 'Ce n\'est pas votre tour'], 400);
        }

        $playerKey = $battle->player1_id === $user->id ? 'player1' : 'player2';
        $opponentKey = $playerKey === 'player1' ? 'player2' : 'player1';
        $state = $battle->battle_state;

        if (!$state) {
            return response()->json(['success' => false, 'message' => 'État du combat invalide'], 500);
        }

        $attackerIndex = $request->attacker_index;
        $targetIndex = $request->target_index;

        // Vérifier que le joueur a des cartes sur le terrain
        if (empty($state[$playerKey]['field'])) {
            return response()->json(['success' => false, 'message' => 'Vous n\'avez pas de cartes sur le terrain'], 400);
        }

        // Vérifier que l'adversaire a des cartes sur le terrain
        if (empty($state[$opponentKey]['field'])) {
            return response()->json(['success' => false, 'message' => 'L\'adversaire n\'a pas de cartes sur le terrain'], 400);
        }

        if (!isset($state[$playerKey]['field'][$attackerIndex])) {
            return response()->json(['success' => false, 'message' => 'Attaquant non trouvé'], 400);
        }

        if (!isset($state[$opponentKey]['field'][$targetIndex])) {
            return response()->json(['success' => false, 'message' => 'Cible non trouvée'], 400);
        }

        $attacker = &$state[$playerKey]['field'][$attackerIndex];
        $target = &$state[$opponentKey]['field'][$targetIndex];

        if ($attacker['has_attacked']) {
            return response()->json(['success' => false, 'message' => 'Cette carte a déjà attaqué ce tour'], 400);
        }

        // Déterminer l'attaque utilisée selon le type
        $attackType = $request->attack_type;
        $attack = null;
        $isComboAttack = false;
        $comboName = null;

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
                return response()->json(['success' => false, 'message' => 'Combo non trouvé'], 400);
            }

            // Vérifier si le combo a déjà été utilisé par ce joueur
            $usedCombos = $state['used_combos'][$playerKey] ?? [];
            if (in_array($comboId, $usedCombos)) {
                return response()->json(['success' => false, 'message' => 'Ce combo a déjà été utilisé dans cette partie'], 400);
            }

            // Vérifier que la carte est le leader
            if ($attacker['id'] !== $combo['leader_card_id']) {
                return response()->json(['success' => false, 'message' => 'Cette carte n\'est pas le leader du combo'], 400);
            }

            // Vérifier que le combo est actif sur le terrain
            $canUseResult = $this->comboService->canUseCombo(
                $combo,
                $attacker,
                $state[$playerKey]['cosmos'] ?? 0,
                $state[$playerKey]['field']
            );

            if (!$canUseResult['can_use']) {
                return response()->json(['success' => false, 'message' => $canUseResult['reason']], 400);
            }

            // Utiliser l'attaque du combo
            $attack = $combo['attack'];
            $isComboAttack = true;
            $comboName = $combo['name'];
            $usedComboId = $comboId; // Sauvegarder l'ID pour marquer comme utilisé après succès

            // Ajouter les coûts supplémentaires du combo
            $attack['endurance_cost'] = ($attack['endurance_cost'] ?? 0) + ($combo['endurance_cost'] ?? 0);
            $attack['cosmos_cost'] = ($attack['cosmos_cost'] ?? 0) + ($combo['cosmos_cost'] ?? 0);
        } else {
            switch ($attackType) {
                case 'secondary1':
                    $attack = $attacker['secondary_attack_1'] ?? null;
                    break;
                case 'secondary2':
                    $attack = $attacker['secondary_attack_2'] ?? null;
                    break;
                case 'main':
                default:
                    $attack = $attacker['main_attack'] ?? null;
                    break;
            }

            // Si l'attaque n'existe pas, utiliser les valeurs par défaut
            if (!$attack) {
                $attack = ['damage' => 50, 'endurance_cost' => 20, 'cosmos_cost' => 0];
            }
        }

        $enduranceCost = $attack['endurance_cost'] ?? 20;
        $cosmosCost = $attack['cosmos_cost'] ?? 0;

        if (($attacker['current_endurance'] ?? 0) < $enduranceCost) {
            return response()->json(['success' => false, 'message' => 'Endurance insuffisante'], 400);
        }

        if (($state[$playerKey]['cosmos'] ?? 0) < $cosmosCost) {
            return response()->json(['success' => false, 'message' => 'Cosmos insuffisant'], 400);
        }

        $damage = max(0, ($attack['damage'] ?? 50) + ($attacker['power'] ?? 0) - ($target['defense'] ?? 0));

        // Sauvegarder les noms avant modification
        $attackerName = $attacker['name'];
        $targetName = $target['name'];

        $target['current_hp'] -= $damage;
        $attacker['current_endurance'] -= $enduranceCost;
        $state[$playerKey]['cosmos'] -= $cosmosCost;
        $attacker['has_attacked'] = true;

        // Marquer le combo comme utilisé (une seule utilisation par partie)
        if ($isComboAttack && isset($usedComboId)) {
            if (!isset($state['used_combos'])) {
                $state['used_combos'] = ['player1' => [], 'player2' => []];
            }
            if (!isset($state['used_combos'][$playerKey])) {
                $state['used_combos'][$playerKey] = [];
            }
            $state['used_combos'][$playerKey][] = $usedComboId;
        }

        $message = $isComboAttack
            ? "⚡ COMBO {$comboName} ! {$attackerName} inflige {$damage} dégâts à {$targetName}"
            : "{$attackerName} inflige {$damage} dégâts à {$targetName}";
        $battleEnded = false;
        $winner = null;
        $targetDestroyed = false;
        $rankPromotion = null;

        // IMPORTANT: libérer les références AVANT de modifier le tableau
        unset($attacker);
        unset($target);

        if ($state[$opponentKey]['field'][$targetIndex]['current_hp'] <= 0) {
            $targetDestroyed = true;
            array_splice($state[$opponentKey]['field'], $targetIndex, 1);
            $message .= " - {$targetName} vaincu !";

            // Vérifier victoire
            if (empty($state[$opponentKey]['field']) && empty($state[$opponentKey]['hand']) && empty($state[$opponentKey]['deck'])) {
                $battleEnded = true;
                $winner = $user->id;
                
                $battle->update([
                    'status' => 'finished',
                    'winner_id' => $winner,
                    'finished_at' => now(),
                ]);

                // Récompenses
                $user->coins += 150;
                $user->wins++;
                $user->save();

                // Vérifier le changement de rang
                $rankPromotion = $user->checkAndUpdateRank();

                $opponent = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                $opponent->coins += 25;
                $opponent->losses++;
                $opponent->save();

                // Gerer le match de tournoi si applicable
                $tournamentMatch = TournamentMatch::where('battle_id', $battle->id)->first();
                if ($tournamentMatch) {
                    $this->tournamentService->processMatchResult($tournamentMatch, $user->id);
                }
            }
        }

        $battle->update([
            'battle_state' => $state,
            'last_action_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'damage' => $damage,
            'message' => $message,
            'target_destroyed' => $targetDestroyed,
            'battle_ended' => $battleEnded,
            'winner' => $winner,
            'rank_promotion' => $rankPromotion ?? null,
            'battle_state' => $state,
        ]);
    }

    public function endTurn(Request $request): JsonResponse
    {
        $request->validate([
            'battle_id' => 'required|exists:battles,id',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $battle = Battle::findOrFail($request->battle_id);

        // Vérifier que l'utilisateur participe à ce combat
        if ($battle->player1_id !== $user->id && $battle->player2_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Vous ne participez pas à ce combat'], 403);
        }

        if (!$battle->isPlayerTurn($user)) {
            return response()->json(['success' => false, 'message' => 'Ce n\'est pas votre tour'], 400);
        }

        $playerKey = $battle->player1_id === $user->id ? 'player1' : 'player2';
        $opponentKey = $playerKey === 'player1' ? 'player2' : 'player1';
        $nextUserId = $playerKey === 'player1' ? $battle->player2_id : $battle->player1_id;

        $state = $battle->battle_state;

        // Pioche pour l'adversaire
        if (!empty($state[$opponentKey]['deck'])) {
            $drawnCard = array_shift($state[$opponentKey]['deck']);
            $state[$opponentKey]['hand'][] = $drawnCard;
        }

        // Cosmos de l'adversaire
        $state[$opponentKey]['max_cosmos'] = min(10, $state[$opponentKey]['max_cosmos'] + 1);
        $state[$opponentKey]['cosmos'] = $state[$opponentKey]['max_cosmos'];

        // Reset des cartes adverses
        foreach ($state[$opponentKey]['field'] as &$card) {
            $card['has_attacked'] = false;
            $card['current_endurance'] = min($card['endurance'], ($card['current_endurance'] ?? 0) + 30);
        }
        unset($card); // IMPORTANT: libérer la référence pour éviter le bug de dédoublement

        $battle->update([
            'battle_state' => $state,
            'current_turn_user_id' => $nextUserId,
            'turn_number' => $battle->turn_number + 1,
            'last_action_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'battle_state' => $state,
        ]);
    }
}
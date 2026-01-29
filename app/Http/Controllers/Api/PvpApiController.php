<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PvpApiController extends Controller
{
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

        if (count($state[$playerKey]['field']) >= 3) {
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

        // Vérifier l'endurance
        $attack = $attacker['main_attack'] ?? ['damage' => 50, 'endurance_cost' => 20, 'cosmos_cost' => 0];
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

        $message = "{$attackerName} inflige {$damage} dégâts à {$targetName}";
        $battleEnded = false;
        $winner = null;

        if ($target['current_hp'] <= 0) {
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

                $opponent = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                $opponent->coins += 25;
                $opponent->losses++;
                $opponent->save();
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
            'battle_ended' => $battleEnded,
            'winner' => $winner,
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
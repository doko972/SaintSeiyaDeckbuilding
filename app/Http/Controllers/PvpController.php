<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\Deck;
use App\Models\Music;
use App\Models\User;
use App\Services\ComboService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PvpController extends Controller
{
    protected ComboService $comboService;

    public function __construct(ComboService $comboService)
    {
        $this->comboService = $comboService;
    }

    /**
     * Affiche le lobby PvP
     */
    public function lobby(): View|RedirectResponse
    {
        $user = auth()->user();
        
        $activeBattle = $user->activeBattle();
        if ($activeBattle) {
            return redirect()->route('pvp.battle', $activeBattle);
        }

        // Combats en attente d'adversaire (pas les nôtres)
        $waitingBattles = Battle::where('status', 'waiting')
            ->where('player1_id', '!=', $user->id)
            ->with('player1', 'player1Deck')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Decks du joueur
        $decks = $user->decks()->withCount('cards')->get();

        // Decks formatés pour le JavaScript
        $decksForJs = $decks->map(function($d) {
            return [
                'id' => $d->id,
                'name' => $d->name,
                'cards_count' => $d->cards_count,
                'is_active' => $d->is_active,
            ];
        })->values();

        // Joueurs en ligne (simplification : actifs dans les dernières 5 minutes)
        $onlinePlayers = User::where('id', '!=', $user->id)
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->limit(20)
            ->get();

        // Statistiques PvP du joueur (basées sur les battles terminées)
        $pvpWins = Battle::where('status', 'finished')
            ->where('winner_id', $user->id)
            ->count();

        $pvpLosses = Battle::where('status', 'finished')
            ->where(function($query) use ($user) {
                $query->where('player1_id', $user->id)
                    ->orWhere('player2_id', $user->id);
            })
            ->where('winner_id', '!=', $user->id)
            ->count();

        $stats = [
            'wins' => $pvpWins,
            'losses' => $pvpLosses,
            'total' => $pvpWins + $pvpLosses,
        ];

        return view('pvp.lobby', compact('waitingBattles', 'decks', 'decksForJs', 'onlinePlayers', 'stats'));
    }

    /**
     * Créer une partie et attendre un adversaire
     */
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
        ]);

        $user = auth()->user();
        $deck = Deck::findOrFail($request->deck_id);

        // Vérifier que le deck appartient au joueur
        if ($deck->user_id !== $user->id) {
            return back()->with('error', 'Ce deck ne vous appartient pas.');
        }

        // Vérifier que le joueur n'est pas déjà dans un combat
        if ($user->isInBattle()) {
            return back()->with('error', 'Vous êtes déjà dans un combat.');
        }

        // Créer le combat
        $battle = Battle::create([
            'player1_id' => $user->id,
            'player1_deck_id' => $deck->id,
            'status' => 'waiting',
        ]);

        return redirect()->route('pvp.waiting', $battle);
    }

    /**
     * Rejoindre une partie existante
     */
    public function join(Request $request, Battle $battle): RedirectResponse
    {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
        ]);

        $user = auth()->user();
        $deck = Deck::findOrFail($request->deck_id);

        // Vérifications
        if ($deck->user_id !== $user->id) {
            return back()->with('error', 'Ce deck ne vous appartient pas.');
        }

        if (!$battle->isWaiting()) {
            return back()->with('error', 'Cette partie n\'est plus disponible.');
        }

        if ($battle->player1_id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas rejoindre votre propre partie.');
        }

        if ($user->isInBattle()) {
            return back()->with('error', 'Vous êtes déjà dans un combat.');
        }

        // Rejoindre le combat
        $battle->update([
            'player2_id' => $user->id,
            'player2_deck_id' => $deck->id,
            'status' => 'in_progress',
            'current_turn_user_id' => $battle->player1_id, // Player 1 commence
            'started_at' => now(),
        ]);

        // Initialiser l'état du combat
        $battleState = $this->initializeBattleState($battle);
        $battle->update(['battle_state' => $battleState]);

        return redirect()->route('pvp.battle', $battle);
    }

    /**
     * Page d'attente
     */
    public function waiting(Battle $battle): View|RedirectResponse
    {
        $user = auth()->user();

        if ($battle->player1_id !== $user->id) {
            return redirect()->route('pvp.lobby');
        }

        if ($battle->isInProgress()) {
            return redirect()->route('pvp.battle', $battle);
        }

        if ($battle->isFinished() || $battle->status === 'cancelled') {
            return redirect()->route('pvp.lobby')->with('info', 'La partie a été annulée.');
        }

        return view('pvp.waiting', compact('battle'));
    }

    /**
     * Annuler une partie en attente
     */
    public function cancel(Battle $battle): RedirectResponse
    {
        $user = auth()->user();

        if ($battle->player1_id !== $user->id) {
            return back()->with('error', 'Action non autorisée.');
        }

        if (!$battle->isWaiting()) {
            return back()->with('error', 'Impossible d\'annuler cette partie.');
        }

        $battle->update(['status' => 'cancelled']);

        return redirect()->route('pvp.lobby')->with('success', 'Partie annulée.');
    }

    /**
     * Abandonner un combat en cours
     */
    public function forfeit(Battle $battle): RedirectResponse
    {
        $user = auth()->user();

        // Vérifier que le joueur participe à ce combat
        if ($battle->player1_id !== $user->id && $battle->player2_id !== $user->id) {
            return redirect()->route('pvp.lobby')->with('error', 'Action non autorisée.');
        }

        if (!$battle->isInProgress()) {
            return redirect()->route('pvp.lobby')->with('error', 'Ce combat n\'est pas en cours.');
        }

        // Déterminer le gagnant (l'adversaire)
        $winnerId = $battle->player1_id === $user->id ? $battle->player2_id : $battle->player1_id;

        // Mettre à jour le combat
        $battle->update([
            'status' => 'finished',
            'winner_id' => $winnerId,
            'finished_at' => now(),
        ]);

        // Récompenses : le perdant (celui qui abandonne) reçoit moins
        $user->losses++;
        $user->save();

        // Le gagnant reçoit la récompense
        $winnerUser = $winnerId === $battle->player1_id ? $battle->player1 : $battle->player2;
        $winnerUser->coins += 150;
        $winnerUser->wins++;
        $winnerUser->save();

        // Vérifier le changement de rang pour le gagnant
        $winnerUser->checkAndUpdateRank();

        return redirect()->route('pvp.lobby')->with('info', 'Vous avez abandonné le combat.');
    }

    /**
     * Page de combat PvP
     */
    public function battle(Battle $battle): View|RedirectResponse
    {
        $user = auth()->user();

        // Vérifier que le joueur participe à ce combat
        if ($battle->player1_id !== $user->id && $battle->player2_id !== $user->id) {
            return redirect()->route('pvp.lobby');
        }

        if ($battle->isWaiting()) {
            return redirect()->route('pvp.waiting', $battle);
        }

        if ($battle->isFinished()) {
            $isWinner = $battle->winner_id === $user->id;
            $resultMusic = $isWinner ? Music::getRandomVictoryMusic() : Music::getRandomDefeatMusic();
            return view('pvp.result', compact('battle', 'resultMusic'));
        }

        $battle->load('player1', 'player2', 'player1Deck', 'player2Deck');

        // Safeguard: Initialize battle_state if null (can happen with old battles)
        if ($battle->battle_state === null && $battle->isInProgress()) {
            $battleState = $this->initializeBattleState($battle);
            $battle->update(['battle_state' => $battleState]);
            $battle->refresh();
        }

        // If still null, redirect to lobby
        if ($battle->battle_state === null) {
            return redirect()->route('pvp.lobby')->with('error', 'Erreur: combat invalide.');
        }

        $playerNumber = $battle->getPlayerNumber($user);
        $opponent = $battle->getOpponent($user);
        $isMyTurn = $battle->isPlayerTurn($user);

        // Récupérer les musiques de combat actives
        $battleMusics = Music::getBattleMusics();
        $victoryMusic = Music::getRandomVictoryMusic();
        $defeatMusic = Music::getRandomDefeatMusic();

        return view('pvp.battle', compact('battle', 'playerNumber', 'opponent', 'isMyTurn', 'battleMusics', 'victoryMusic', 'defeatMusic'));
    }

    /**
     * Initialiser l'état du combat
     */
    private function initializeBattleState(Battle $battle): array
    {
        $player1Deck = $this->prepareDeck($battle->player1Deck);
        $player2Deck = $this->prepareDeck($battle->player2Deck);

        shuffle($player1Deck);
        shuffle($player2Deck);

        // Main initiale (5 cartes)
        $player1Hand = array_splice($player1Deck, 0, min(5, count($player1Deck)));
        $player2Hand = array_splice($player2Deck, 0, min(5, count($player2Deck)));

        return [
            'turn' => 1,
            'player1' => [
                'deck' => $player1Deck,
                'hand' => $player1Hand,
                'field' => [],
                'cosmos' => 5,
                'max_cosmos' => 5,
            ],
            'player2' => [
                'deck' => $player2Deck,
                'hand' => $player2Hand,
                'field' => [],
                'cosmos' => 5,
                'max_cosmos' => 5,
            ],
            // Charger tous les combos actifs pour le combat
            'all_combos' => $this->comboService->getAllActiveCombos(),
            // Tracker les combos utilisés (une seule utilisation par partie)
            'used_combos' => [
                'player1' => [],
                'player2' => [],
            ],
        ];
    }

    /**
     * Préparer le deck pour le combat
     */
    private function prepareDeck(Deck $deck): array
    {
        $deck->load('cards.faction', 'cards.mainAttack', 'cards.secondaryAttack1', 'cards.secondaryAttack2');
        
        $cards = [];
        foreach ($deck->cards as $card) {
            $quantity = $card->pivot->quantity ?? 1;
            for ($i = 0; $i < $quantity; $i++) {
                $cards[] = [
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
                    'image' => $card->image_primary ? \Storage::url($card->image_primary) : null,
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
                    'rarity' => $card->rarity,
                    'can_attack' => false,
                    'has_attacked' => false,
                    'instance_id' => uniqid('card_'),
                ];
            }
        }

        return $cards;
    }
}
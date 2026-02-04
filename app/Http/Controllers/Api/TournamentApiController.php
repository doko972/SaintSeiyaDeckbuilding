<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\Deck;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TournamentApiController extends Controller
{
    protected TournamentService $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    /**
     * Liste des tournois actifs
     */
    public function list(): JsonResponse
    {
        $tournaments = Tournament::whereIn('status', ['registration', 'in_progress'])
            ->withCount('participants')
            ->orderBy('is_featured', 'desc')
            ->orderBy('start_at', 'asc')
            ->get();

        return response()->json(['tournaments' => $tournaments]);
    }

    /**
     * Detail d'un tournoi avec bracket
     */
    public function show(Tournament $tournament): JsonResponse
    {
        $tournament->load(['participants.user', 'matches.participant1.user', 'matches.participant2.user']);

        return response()->json([
            'tournament' => $tournament,
            'bracket' => $this->formatBracket($tournament),
        ]);
    }

    /**
     * Inscription a un tournoi
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'deck_id' => 'required|exists:decks,id',
        ]);

        $user = auth()->user();
        $tournament = Tournament::findOrFail($request->tournament_id);
        $deck = Deck::findOrFail($request->deck_id);

        $result = $this->tournamentService->register($tournament, $user, $deck);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Retrait d'un tournoi
     */
    public function withdraw(Request $request): JsonResponse
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        $user = auth()->user();
        $tournament = Tournament::findOrFail($request->tournament_id);

        $result = $this->tournamentService->withdraw($tournament, $user);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Obtenir les infos d'un match
     */
    public function getMatch(TournamentMatch $match): JsonResponse
    {
        $user = auth()->user();

        if (!$match->involvesUser($user)) {
            return response()->json(['error' => 'Non autorise'], 403);
        }

        $match->load(['participant1.user', 'participant2.user', 'battle', 'tournament']);

        return response()->json(['match' => $match]);
    }

    /**
     * Marquer le joueur comme pret pour un match
     */
    public function setReady(TournamentMatch $match): JsonResponse
    {
        $user = auth()->user();

        if (!$match->involvesUser($user)) {
            return response()->json(['error' => 'Non autorise'], 403);
        }

        if (!$match->isReady()) {
            return response()->json(['error' => 'Le match n\'est pas pret'], 400);
        }

        // Stocker le statut "pret" dans la session
        $sessionKey = "tournament_match_{$match->id}_ready";
        $readyPlayers = session($sessionKey, []);

        if (!in_array($user->id, $readyPlayers)) {
            $readyPlayers[] = $user->id;
            session([$sessionKey => $readyPlayers]);
        }

        return response()->json([
            'success' => true,
            'ready_count' => count($readyPlayers),
            'both_ready' => count($readyPlayers) >= 2,
        ]);
    }

    /**
     * Verifier le statut de preparation d'un match
     */
    public function checkReady(TournamentMatch $match): JsonResponse
    {
        $user = auth()->user();

        if (!$match->involvesUser($user)) {
            return response()->json(['error' => 'Non autorise'], 403);
        }

        // Si le match a deja un combat en cours
        if ($match->battle_id) {
            $match->load('battle');
            return response()->json([
                'status' => 'battle_started',
                'battle_id' => $match->battle_id,
            ]);
        }

        $sessionKey = "tournament_match_{$match->id}_ready";
        $readyPlayers = session($sessionKey, []);

        $player1Ready = $match->participant1 && in_array($match->participant1->user_id, $readyPlayers);
        $player2Ready = $match->participant2 && in_array($match->participant2->user_id, $readyPlayers);

        return response()->json([
            'status' => 'waiting',
            'player1_ready' => $player1Ready,
            'player2_ready' => $player2Ready,
            'both_ready' => $player1Ready && $player2Ready,
            'i_am_ready' => in_array($user->id, $readyPlayers),
        ]);
    }

    /**
     * Demarrer le combat d'un match
     */
    public function startMatch(TournamentMatch $match): JsonResponse
    {
        $user = auth()->user();

        if (!$match->involvesUser($user)) {
            return response()->json(['error' => 'Non autorise'], 403);
        }

        if (!$match->isReady()) {
            return response()->json(['error' => 'Le match n\'est pas pret'], 400);
        }

        if ($match->battle_id) {
            return response()->json([
                'success' => true,
                'battle_id' => $match->battle_id,
            ]);
        }

        $battle = $this->tournamentService->createMatchBattle($match);

        if (!$battle) {
            return response()->json(['error' => 'Impossible de creer le combat'], 500);
        }

        // Nettoyer la session
        session()->forget("tournament_match_{$match->id}_ready");

        return response()->json([
            'success' => true,
            'battle_id' => $battle->id,
        ]);
    }

    /**
     * Obtenir les participations de l'utilisateur
     */
    public function getUserParticipations(): JsonResponse
    {
        $user = auth()->user();

        $participations = $user->tournamentParticipations()
            ->with(['tournament', 'deck'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['participations' => $participations]);
    }

    /**
     * Formater le bracket pour l'affichage
     */
    private function formatBracket(Tournament $tournament): array
    {
        $bracket = [];
        $matches = $tournament->matches()
            ->with(['participant1.user', 'participant2.user', 'winner'])
            ->get()
            ->groupBy('round');

        foreach ($matches as $round => $roundMatches) {
            $bracket[$round] = [
                'name' => $tournament->getRoundName($round),
                'matches' => $roundMatches->map(fn($m) => [
                    'id' => $m->id,
                    'bracket_code' => $m->bracket_code,
                    'player1' => $m->participant1?->user->name ?? 'TBD',
                    'player1_id' => $m->participant1?->user_id,
                    'player2' => $m->participant2?->user->name ?? 'TBD',
                    'player2_id' => $m->participant2?->user_id,
                    'winner' => $m->winner?->user->name,
                    'winner_id' => $m->winner?->user_id,
                    'status' => $m->status,
                ])->toArray(),
            ];
        }

        return $bracket;
    }
}

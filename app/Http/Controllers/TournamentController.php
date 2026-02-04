<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TournamentController extends Controller
{
    protected TournamentService $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    /**
     * Liste des tournois
     */
    public function index(): View
    {
        $user = auth()->user();

        // Tournois ouverts aux inscriptions
        $registrationTournaments = Tournament::where('status', 'registration')
            ->withCount('participants')
            ->orderBy('is_featured', 'desc')
            ->orderBy('registration_end_at', 'asc')
            ->get();

        // Tournois en cours
        $inProgressTournaments = Tournament::where('status', 'in_progress')
            ->withCount('participants')
            ->orderBy('start_at', 'asc')
            ->get();

        // Tournois termines
        $finishedTournaments = Tournament::where('status', 'finished')
            ->with('participants.user')
            ->withCount('participants')
            ->orderBy('finished_at', 'desc')
            ->limit(5)
            ->get();

        // Mes participations actives
        $myParticipations = $user->tournamentParticipations()
            ->with(['tournament', 'deck'])
            ->whereHas('tournament', function ($query) {
                $query->whereIn('status', ['registration', 'in_progress']);
            })
            ->get();

        // Mes decks pour l'inscription
        $userDecks = $user->decks()->with('cards')->get();

        return view('tournaments.index', compact(
            'registrationTournaments',
            'inProgressTournaments',
            'finishedTournaments',
            'myParticipations',
            'userDecks'
        ));
    }

    /**
     * Detail d'un tournoi
     */
    public function show(Tournament $tournament): View
    {
        $user = auth()->user();

        $tournament->load([
            'participants.user',
            'participants.deck',
            'matches.participant1.user',
            'matches.participant2.user',
            'matches.winner',
        ]);

        $myParticipation = $tournament->participants()
            ->where('user_id', $user->id)
            ->with('deck')
            ->first();

        $myCurrentMatch = $myParticipation?->getCurrentMatch();
        if ($myCurrentMatch) {
            $myCurrentMatch->load(['participant1.user', 'participant2.user', 'participant1.deck', 'participant2.deck']);
        }

        $userDecks = $user->decks()->with('cards')->get();

        return view('tournaments.show', compact('tournament', 'myParticipation', 'myCurrentMatch', 'userDecks'));
    }

    /**
     * Vue du bracket
     */
    public function bracket(Tournament $tournament): View
    {
        $user = auth()->user();

        $tournament->load([
            'participants.user',
            'matches.participant1.user',
            'matches.participant2.user',
            'matches.winner',
        ]);

        $myParticipation = $tournament->participants()
            ->where('user_id', $user->id)
            ->first();

        return view('tournaments.bracket', compact('tournament', 'myParticipation'));
    }

    /**
     * Salle d'attente d'un match
     */
    public function match(Tournament $tournament, TournamentMatch $match): View|RedirectResponse
    {
        $user = auth()->user();

        // Verifier que le match appartient au tournoi
        if ($match->tournament_id !== $tournament->id) {
            return redirect()->route('tournaments.show', $tournament)
                ->with('error', 'Match invalide.');
        }

        // Verifier que le joueur participe a ce match
        if (!$match->involvesUser($user)) {
            return redirect()->route('tournaments.show', $tournament)
                ->with('error', 'Vous ne participez pas a ce match.');
        }

        // Si le combat existe et est en cours, rediriger vers le combat
        if ($match->battle && $match->battle->isInProgress()) {
            return redirect()->route('pvp.battle', $match->battle);
        }

        $match->load(['participant1.user', 'participant2.user', 'tournament']);

        // Determiner si le joueur actuel est pret
        $userParticipant = $match->participant1->user_id === $user->id
            ? $match->participant1
            : $match->participant2;

        $opponentParticipant = $match->participant1->user_id === $user->id
            ? $match->participant2
            : $match->participant1;

        return view('tournaments.match', compact('tournament', 'match', 'userParticipant', 'opponentParticipant'));
    }
}

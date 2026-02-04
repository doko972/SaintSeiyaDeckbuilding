<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Card;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TournamentController extends Controller
{
    protected TournamentService $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    public function index(): View
    {
        $tournaments = Tournament::withCount('participants')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create(): View
    {
        $cards = Card::whereIn('rarity', ['epic', 'legendary'])->orderBy('name')->get();
        return view('admin.tournaments.create', compact('cards'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_players' => 'required|in:8,16,32',
            'registration_start_at' => 'required|date',
            'registration_end_at' => 'required|date|after:registration_start_at',
            'entry_fee' => 'required|integer|min:0',
            'min_deck_cards' => 'required|integer|min:5|max:30',
            'winner_coins' => 'required|integer|min:0',
            'runner_up_coins' => 'required|integer|min:0',
            'semifinalist_coins' => 'required|integer|min:0',
            'participation_coins' => 'required|integer|min:0',
            'exclusive_card_id' => 'nullable|exists:cards,id',
            'winner_title' => 'nullable|string|max:100',
        ]);

        Tournament::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => 'single_elimination',
            'status' => Tournament::STATUS_DRAFT,
            'max_players' => $validated['max_players'],
            'total_rounds' => (int) log($validated['max_players'], 2),
            'registration_start_at' => $validated['registration_start_at'],
            'registration_end_at' => $validated['registration_end_at'],
            'entry_fee' => $validated['entry_fee'],
            'min_deck_cards' => $validated['min_deck_cards'],
            'is_featured' => $request->has('is_featured'),
            'rewards_config' => [
                'winner_coins' => $validated['winner_coins'],
                'runner_up_coins' => $validated['runner_up_coins'],
                'semifinalist_coins' => $validated['semifinalist_coins'],
                'participation_coins' => $validated['participation_coins'],
                'exclusive_card_id' => $validated['exclusive_card_id'],
                'winner_title' => $validated['winner_title'],
            ],
        ]);

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournoi cree avec succes !');
    }

    public function show(Tournament $tournament): View
    {
        $tournament->load([
            'participants.user',
            'participants.deck',
            'matches.participant1.user',
            'matches.participant2.user',
            'matches.winner',
        ]);
        return view('admin.tournaments.show', compact('tournament'));
    }

    public function edit(Tournament $tournament): View|RedirectResponse
    {
        if (!in_array($tournament->status, [Tournament::STATUS_DRAFT, Tournament::STATUS_REGISTRATION])) {
            return redirect()->route('admin.tournaments.show', $tournament)
                ->with('error', 'Impossible de modifier un tournoi en cours ou termine.');
        }

        $cards = Card::whereIn('rarity', ['epic', 'legendary'])->orderBy('name')->get();
        return view('admin.tournaments.edit', compact('tournament', 'cards'));
    }

    public function update(Request $request, Tournament $tournament): RedirectResponse
    {
        if (!in_array($tournament->status, [Tournament::STATUS_DRAFT, Tournament::STATUS_REGISTRATION])) {
            return back()->with('error', 'Impossible de modifier un tournoi en cours ou termine.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_players' => 'required|in:8,16,32',
            'registration_start_at' => 'required|date',
            'registration_end_at' => 'required|date|after:registration_start_at',
            'entry_fee' => 'required|integer|min:0',
            'min_deck_cards' => 'required|integer|min:5|max:30',
            'winner_coins' => 'required|integer|min:0',
            'runner_up_coins' => 'required|integer|min:0',
            'semifinalist_coins' => 'required|integer|min:0',
            'participation_coins' => 'required|integer|min:0',
            'exclusive_card_id' => 'nullable|exists:cards,id',
            'winner_title' => 'nullable|string|max:100',
        ]);

        $tournament->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'max_players' => $validated['max_players'],
            'total_rounds' => (int) log($validated['max_players'], 2),
            'registration_start_at' => $validated['registration_start_at'],
            'registration_end_at' => $validated['registration_end_at'],
            'entry_fee' => $validated['entry_fee'],
            'min_deck_cards' => $validated['min_deck_cards'],
            'is_featured' => $request->has('is_featured'),
            'rewards_config' => [
                'winner_coins' => $validated['winner_coins'],
                'runner_up_coins' => $validated['runner_up_coins'],
                'semifinalist_coins' => $validated['semifinalist_coins'],
                'participation_coins' => $validated['participation_coins'],
                'exclusive_card_id' => $validated['exclusive_card_id'],
                'winner_title' => $validated['winner_title'],
            ],
        ]);

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournoi mis a jour !');
    }

    public function destroy(Tournament $tournament): RedirectResponse
    {
        if ($tournament->status === Tournament::STATUS_IN_PROGRESS) {
            return back()->with('error', 'Impossible de supprimer un tournoi en cours.');
        }

        // Rembourser si necessaire
        if ($tournament->entry_fee > 0 && $tournament->status === Tournament::STATUS_REGISTRATION) {
            foreach ($tournament->participants as $participant) {
                $participant->user->addCoins($tournament->entry_fee);
            }
        }

        $tournament->delete();
        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournoi supprime.');
    }

    public function openRegistration(Tournament $tournament): RedirectResponse
    {
        if ($tournament->status !== Tournament::STATUS_DRAFT) {
            return back()->with('error', 'Action non autorisee.');
        }

        $tournament->update(['status' => Tournament::STATUS_REGISTRATION]);
        return back()->with('success', 'Inscriptions ouvertes !');
    }

    public function start(Tournament $tournament): RedirectResponse
    {
        $result = $this->tournamentService->start($tournament);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Tournoi demarre !');
    }

    public function cancel(Tournament $tournament): RedirectResponse
    {
        $result = $this->tournamentService->cancel($tournament);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Tournoi annule. Frais d\'inscription rembourses.');
    }
}

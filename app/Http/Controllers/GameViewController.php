<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameViewController extends Controller
{
    /**
     * Page de sélection du deck avant combat
     */
    public function index(): View
    {
        $decks = auth()->user()->decks()->withCount('cards')->get();
        
        return view('game.index', compact('decks'));
    }

    /**
     * Page de combat
     */
    public function battle(Deck $deck): View
    {
        // Vérifier que le deck appartient à l'utilisateur
        if ($deck->user_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier que le deck a des cartes
        if ($deck->cards()->count() === 0) {
            return redirect()->route('game.index')
                ->with('error', 'Ce deck est vide. Ajoutez des cartes avant de combattre.');
        }

        return view('game.battle', compact('deck'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Attack;
use App\Models\Card;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ComboController extends Controller
{
    /**
     * Afficher la liste des combos
     */
    public function index(): View
    {
        $combos = Combo::with(['card1', 'card2', 'card3', 'leaderCard', 'attack'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.combos.index', compact('combos'));
    }

    /**
     * Formulaire de création
     */
    public function create(): View
    {
        $cards = Card::orderBy('name')->get();
        $attacks = Attack::orderBy('name')->get();

        return view('admin.combos.create', compact('cards', 'attacks'));
    }

    /**
     * Enregistrer un nouveau combo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'card1_id' => 'required|exists:cards,id',
            'card2_id' => 'required|exists:cards,id|different:card1_id',
            'card3_id' => 'required|exists:cards,id|different:card1_id|different:card2_id',
            'leader_card_id' => 'required|exists:cards,id',
            'attack_id' => 'required|exists:attacks,id',
            'endurance_cost' => 'required|integer|min:0',
            'cosmos_cost' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Vérifier que le leader fait partie des 3 cartes
        $cardIds = [$validated['card1_id'], $validated['card2_id'], $validated['card3_id']];
        if (!in_array($validated['leader_card_id'], $cardIds)) {
            return back()->withErrors(['leader_card_id' => 'Le leader doit être une des 3 cartes du combo.'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        Combo::create($validated);

        return redirect()->route('admin.combos.index')->with('success', 'Combo créé avec succès !');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Combo $combo): View
    {
        $cards = Card::orderBy('name')->get();
        $attacks = Attack::orderBy('name')->get();

        return view('admin.combos.edit', compact('combo', 'cards', 'attacks'));
    }

    /**
     * Mettre à jour un combo
     */
    public function update(Request $request, Combo $combo): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'card1_id' => 'required|exists:cards,id',
            'card2_id' => 'required|exists:cards,id|different:card1_id',
            'card3_id' => 'required|exists:cards,id|different:card1_id|different:card2_id',
            'leader_card_id' => 'required|exists:cards,id',
            'attack_id' => 'required|exists:attacks,id',
            'endurance_cost' => 'required|integer|min:0',
            'cosmos_cost' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Vérifier que le leader fait partie des 3 cartes
        $cardIds = [$validated['card1_id'], $validated['card2_id'], $validated['card3_id']];
        if (!in_array($validated['leader_card_id'], $cardIds)) {
            return back()->withErrors(['leader_card_id' => 'Le leader doit être une des 3 cartes du combo.'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        $combo->update($validated);

        return redirect()->route('admin.combos.index')->with('success', 'Combo mis à jour avec succès !');
    }

    /**
     * Supprimer un combo
     */
    public function destroy(Combo $combo): RedirectResponse
    {
        $combo->delete();

        return redirect()->route('admin.combos.index')->with('success', 'Combo supprimé avec succès !');
    }

    /**
     * Activer/Désactiver un combo
     */
    public function toggle(Combo $combo): RedirectResponse
    {
        $combo->update(['is_active' => !$combo->is_active]);

        $status = $combo->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Combo {$status} avec succès !");
    }
}

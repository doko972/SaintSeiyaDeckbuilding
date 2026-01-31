<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeckController extends Controller
{
    /**
     * Affiche la liste des decks de l'utilisateur
     */
    public function index(): View
    {
        $decks = auth()->user()->decks()->withCount('cards')->orderBy('name')->get();
        
        return view('decks.index', compact('decks'));
    }

    /**
     * Affiche le formulaire de création
     */
public function create(): View
{
    $collection = auth()->user()->cards()->with('faction')->get();
    
    return view('decks.create', compact('collection'));
}

    /**
     * Enregistre un nouveau deck
     */
    public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
        'cards' => 'nullable|array',
        'cards.*' => 'integer|min:0',
    ]);

    // Vérifier la limite de 7 cartes maximum
    $totalCards = !empty($validated['cards']) ? array_sum($validated['cards']) : 0;
    if ($totalCards > 7) {
        return back()->withErrors(['cards' => 'Un deck ne peut pas contenir plus de 7 cartes.'])->withInput();
    }

    // Si ce deck devient actif, désactiver les autres
    if ($request->boolean('is_active')) {
        auth()->user()->decks()->update(['is_active' => false]);
    }

    $deck = auth()->user()->decks()->create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'is_active' => $request->boolean('is_active'),
    ]);

    // Attacher les cartes au deck
    // Format reçu : cards[card_id] = quantity
    if (!empty($validated['cards'])) {
        $cardsToAttach = [];
        
        foreach ($validated['cards'] as $cardId => $quantity) {
            $quantity = (int) $quantity;
            
            if ($quantity > 0) {
                // Vérifier que l'utilisateur possède cette carte
                $userCard = auth()->user()->cards()->where('card_id', $cardId)->first();
                
                if ($userCard && $userCard->pivot->quantity >= $quantity) {
                    $cardsToAttach[$cardId] = ['quantity' => $quantity];
                }
            }
        }
        
        if (!empty($cardsToAttach)) {
            $deck->cards()->attach($cardsToAttach);
        }
    }

    return redirect()->route('decks.show', $deck)
        ->with('success', 'Deck créé avec succès.');
}

    /**
     * Affiche le détail d'un deck
     */
    public function show(Deck $deck): View
    {
        // Vérifier que le deck appartient à l'utilisateur
        $this->authorize('view', $deck);
        
        $deck->load('cards.faction', 'cards.mainAttack');
        
        return view('decks.show', compact('deck'));
    }

    /**
     * Affiche le formulaire d'édition
     */
public function edit(Deck $deck): View
{
    // Vérifier que le deck appartient à l'utilisateur
    if ($deck->user_id !== auth()->id()) {
        abort(403);
    }

    $collection = auth()->user()->cards()->with('faction')->get();
    $deck->load('cards');
    
    return view('decks.edit', compact('deck', 'collection'));
}

    /**
     * Met à jour un deck
     */
    public function update(Request $request, Deck $deck): RedirectResponse
{
    $this->authorize('update', $deck);

    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
        'cards' => 'nullable|array',
        'cards.*' => 'integer|min:0',
    ]);

    // Vérifier la limite de 7 cartes maximum
    $totalCards = !empty($validated['cards']) ? array_sum($validated['cards']) : 0;
    if ($totalCards > 7) {
        return back()->withErrors(['cards' => 'Un deck ne peut pas contenir plus de 7 cartes.'])->withInput();
    }

    // Si ce deck devient actif, désactiver les autres
    if ($request->boolean('is_active')) {
        auth()->user()->decks()->where('id', '!=', $deck->id)->update(['is_active' => false]);
    }

    $deck->update([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'is_active' => $request->boolean('is_active'),
    ]);

    // Synchroniser les cartes
    // Format reçu : cards[card_id] = quantity
    $cardsToSync = [];
    
    if (!empty($validated['cards'])) {
        foreach ($validated['cards'] as $cardId => $quantity) {
            $quantity = (int) $quantity;
            
            if ($quantity > 0) {
                // Vérifier que l'utilisateur possède cette carte
                $userCard = auth()->user()->cards()->where('card_id', $cardId)->first();
                
                if ($userCard && $userCard->pivot->quantity >= $quantity) {
                    $cardsToSync[$cardId] = ['quantity' => $quantity];
                }
            }
        }
    }
    
    $deck->cards()->sync($cardsToSync);

    return redirect()->route('decks.show', $deck)
        ->with('success', 'Deck mis à jour avec succès.');
}

    /**
     * Supprime un deck
     */
    public function destroy(Deck $deck): RedirectResponse
    {
        $this->authorize('delete', $deck);

        $deck->delete();

        return redirect()->route('decks.index')
            ->with('success', 'Deck supprimé avec succès.');
    }
}
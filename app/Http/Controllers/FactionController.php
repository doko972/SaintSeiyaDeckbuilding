<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class FactionController extends Controller
{
    /**
     * Affiche la liste des factions
     */
    public function index(): View
    {
        $factions = Faction::orderBy('name')->get();
        
        return view('factions.index', compact('factions'));
    }

    /**
     * Affiche le formulaire de création (Admin)
     */
    public function create(): View
    {
        return view('factions.create');
    }

    /**
     * Enregistre une nouvelle faction (Admin)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:factions',
            'description' => 'nullable|string',
            'color_primary' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secondary' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('factions', 'public');
        }

        Faction::create($validated);

        return redirect()->route('factions.index')
            ->with('success', 'Faction créée avec succès.');
    }

    /**
     * Affiche le détail d'une faction
     */
    public function show(Faction $faction): View
    {
        $faction->load('cards');
        
        return view('factions.show', compact('faction'));
    }

    /**
     * Affiche le formulaire d'édition (Admin)
     */
    public function edit(Faction $faction): View
    {
        return view('factions.edit', compact('faction'));
    }

    /**
     * Met à jour une faction (Admin)
     */
    public function update(Request $request, Faction $faction): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:factions,name,' . $faction->id,
            'description' => 'nullable|string',
            'color_primary' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secondary' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($faction->image) {
                Storage::disk('public')->delete($faction->image);
            }
            $validated['image'] = $request->file('image')->store('factions', 'public');
        }

        $faction->update($validated);

        return redirect()->route('factions.index')
            ->with('success', 'Faction mise à jour avec succès.');
    }

    /**
     * Supprime une faction (Admin)
     */
    public function destroy(Faction $faction): RedirectResponse
    {
        if ($faction->image) {
            Storage::disk('public')->delete($faction->image);
        }

        $faction->delete();

        return redirect()->route('factions.index')
            ->with('success', 'Faction supprimée avec succès.');
    }
}
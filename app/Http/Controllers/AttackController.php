<?php

namespace App\Http\Controllers;

use App\Models\Attack;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttackController extends Controller
{
    /**
     * Types d'effets disponibles
     */
    public static function getEffectTypes(): array
    {
        return [
            'none' => 'Aucun',
            'burn' => 'Brûlure',
            'freeze' => 'Gel',
            'stun' => 'Étourdissement',
            'heal' => 'Soin',
            'buff_attack' => 'Boost Attaque',
            'buff_defense' => 'Boost Défense',
            'debuff' => 'Affaiblissement',
            'drain' => 'Drain de vie',
        ];
    }

    /**
     * Affiche la liste des attaques
     */
    public function index(): View
    {
        $attacks = Attack::orderBy('name')->get();
        
        return view('attacks.index', compact('attacks'));
    }

    /**
     * Affiche le formulaire de création (Admin)
     */
    public function create(): View
    {
        $effectTypes = self::getEffectTypes();
        
        return view('attacks.create', compact('effectTypes'));
    }

    /**
     * Enregistre une nouvelle attaque (Admin)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'damage' => 'required|integer|min:0',
            'endurance_cost' => 'required|integer|min:0',
            'cosmos_cost' => 'required|integer|min:0',
            'effect_type' => 'required|in:' . implode(',', array_keys(self::getEffectTypes())),
            'effect_value' => 'required|integer|min:0',
        ]);

        Attack::create($validated);

        return redirect()->route('attacks.index')
            ->with('success', 'Attaque créée avec succès.');
    }

    /**
     * Affiche le détail d'une attaque
     */
    public function show(Attack $attack): View
    {
        return view('attacks.show', compact('attack'));
    }

    /**
     * Affiche le formulaire d'édition (Admin)
     */
    public function edit(Attack $attack): View
    {
        $effectTypes = self::getEffectTypes();
        
        return view('attacks.edit', compact('attack', 'effectTypes'));
    }

    /**
     * Met à jour une attaque (Admin)
     */
    public function update(Request $request, Attack $attack): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'damage' => 'required|integer|min:0',
            'endurance_cost' => 'required|integer|min:0',
            'cosmos_cost' => 'required|integer|min:0',
            'effect_type' => 'required|in:' . implode(',', array_keys(self::getEffectTypes())),
            'effect_value' => 'required|integer|min:0',
        ]);

        $attack->update($validated);

        return redirect()->route('attacks.index')
            ->with('success', 'Attaque mise à jour avec succès.');
    }

    /**
     * Supprime une attaque (Admin)
     */
    public function destroy(Attack $attack): RedirectResponse
    {
        $attack->delete();

        return redirect()->route('attacks.index')
            ->with('success', 'Attaque supprimée avec succès.');
    }
}
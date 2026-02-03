<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Faction;
use App\Models\Attack;
use App\Services\FusionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    protected FusionService $fusionService;

    public function __construct(FusionService $fusionService)
    {
        $this->fusionService = $fusionService;
    }

    /**
     * Options pour les selects
     */
    public static function getArmorTypes(): array
    {
        return [
            'bronze' => 'Bronze',
            'silver' => 'Argent',
            'gold' => 'Or',
            'divine' => 'Divin',
        ];
    }

    public static function getElements(): array
    {
        return [
            'fire' => 'Feu',
            'water' => 'Eau',
            'ice' => 'Glace',
            'thunder' => 'Foudre',
            'darkness' => 'Ténèbres',
            'light' => 'Lumière',
        ];
    }

    public static function getRarities(): array
    {
        return [
            'common' => 'Commune',
            'rare' => 'Rare',
            'epic' => 'Épique',
            'legendary' => 'Légendaire',
        ];
    }

    public function index(Request $request): View
    {
        $query = Card::with(['faction', 'mainAttack']);

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtre par faction
        if ($request->filled('faction')) {
            $query->where('faction_id', $request->faction);
        }

        // Filtre par rareté
        if ($request->filled('rarity')) {
            $query->where('rarity', $request->rarity);
        }

        // Filtre par armure
        if ($request->filled('armor')) {
            $query->where('armor_type', $request->armor);
        }

        $cards = $query->orderBy('name')->get();

        return view('cards.index', compact('cards'));
    }

    /**
     * Affiche le formulaire de création (Admin)
     */
    public function create(): View
    {
        $factions = Faction::orderBy('name')->get();
        $attacks = Attack::orderBy('name')->get();
        $armorTypes = self::getArmorTypes();
        $elements = self::getElements();
        $rarities = self::getRarities();

        return view('cards.create', compact(
            'factions',
            'attacks',
            'armorTypes',
            'elements',
            'rarities'
        ));
    }

    /**
     * Enregistre une nouvelle carte (Admin)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'faction_id' => 'required|exists:factions,id',
            'grade' => 'required|integer|min:1|max:10',
            'armor_type' => 'required|in:' . implode(',', array_keys(self::getArmorTypes())),
            'element' => 'required|in:' . implode(',', array_keys(self::getElements())),
            'rarity' => 'required|in:' . implode(',', array_keys(self::getRarities())),
            'health_points' => 'required|integer|min:1',
            'endurance' => 'required|integer|min:1',
            'defense' => 'required|integer|min:0',
            'power' => 'required|integer|min:1',
            'cosmos' => 'required|integer|min:1',
            'cost' => 'required|integer|min:1',
            'passive_ability_name' => 'nullable|string|max:100',
            'passive_ability_description' => 'nullable|string',
            'main_attack_id' => 'required|exists:attacks,id',
            'secondary_attack_1_id' => 'nullable|exists:attacks,id',
            'secondary_attack_2_id' => 'nullable|exists:attacks,id',
            'image_primary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_secondary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image_primary')) {
            $validated['image_primary'] = $request->file('image_primary')->store('cards', 'public');
        }

        if ($request->hasFile('image_secondary')) {
            $validated['image_secondary'] = $request->file('image_secondary')->store('cards', 'public');
        }

        Card::create($validated);

        return redirect()->route('cards.index')
            ->with('success', 'Carte créée avec succès.');
    }

    /**
     * Affiche le détail d'une carte
     */
    public function show(Card $card): View
    {
        $card->load('faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2');

        // Vérifier si l'utilisateur connecté possède cette carte
        $fusionLevel = 1;
        $boostedStats = null;
        $owned = null;

        if (auth()->check()) {
            $user = auth()->user();
            $owned = $user->cards()->where('card_id', $card->id)->first();

            if ($owned) {
                $fusionLevel = $owned->pivot->fusion_level ?? 1;
                if ($fusionLevel > 1) {
                    $boostedStats = $this->fusionService->calculateBoostedStats($card, $fusionLevel);
                }
            }
        }

        return view('cards.show', compact('card', 'fusionLevel', 'boostedStats', 'owned'));
    }

    /**
     * Affiche le formulaire d'édition (Admin)
     */
    public function edit(Card $card): View
    {
        $factions = Faction::orderBy('name')->get();
        $attacks = Attack::orderBy('name')->get();
        $armorTypes = self::getArmorTypes();
        $elements = self::getElements();
        $rarities = self::getRarities();

        return view('cards.edit', compact(
            'card',
            'factions',
            'attacks',
            'armorTypes',
            'elements',
            'rarities'
        ));
    }

    /**
     * Met à jour une carte (Admin)
     */
    public function update(Request $request, Card $card): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'faction_id' => 'required|exists:factions,id',
            'grade' => 'required|integer|min:1|max:10',
            'armor_type' => 'required|in:' . implode(',', array_keys(self::getArmorTypes())),
            'element' => 'required|in:' . implode(',', array_keys(self::getElements())),
            'rarity' => 'required|in:' . implode(',', array_keys(self::getRarities())),
            'health_points' => 'required|integer|min:1',
            'endurance' => 'required|integer|min:1',
            'defense' => 'required|integer|min:0',
            'power' => 'required|integer|min:1',
            'cosmos' => 'required|integer|min:1',
            'cost' => 'required|integer|min:1',
            'passive_ability_name' => 'nullable|string|max:100',
            'passive_ability_description' => 'nullable|string',
            'main_attack_id' => 'required|exists:attacks,id',
            'secondary_attack_1_id' => 'nullable|exists:attacks,id',
            'secondary_attack_2_id' => 'nullable|exists:attacks,id',
            'image_primary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_secondary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image_primary')) {
            if ($card->image_primary) {
                Storage::disk('public')->delete($card->image_primary);
            }
            $validated['image_primary'] = $request->file('image_primary')->store('cards', 'public');
        }

        if ($request->hasFile('image_secondary')) {
            if ($card->image_secondary) {
                Storage::disk('public')->delete($card->image_secondary);
            }
            $validated['image_secondary'] = $request->file('image_secondary')->store('cards', 'public');
        }

        $card->update($validated);

        return redirect()->route('cards.index')
            ->with('success', 'Carte mise à jour avec succès.');
    }

    /**
     * Supprime une carte (Admin)
     */
    public function destroy(Card $card): RedirectResponse
    {
        if ($card->image_primary) {
            Storage::disk('public')->delete($card->image_primary);
        }
        if ($card->image_secondary) {
            Storage::disk('public')->delete($card->image_secondary);
        }

        $card->delete();

        return redirect()->route('cards.index')
            ->with('success', 'Carte supprimée avec succès.');
    }
}
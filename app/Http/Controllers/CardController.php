<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardImage;
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
    public static function getPowerTypes(): array
    {
        return [
            'black' => 'Noir',
            'bronze' => 'Bronze',
            'silver' => 'Argent',
            'gold' => 'Or',
            'divine' => 'Divin',
            'surplis' => 'Surplis',
            'god_warrior' => 'Guerrier Divin',
            'steel' => 'Acier',
        ];
    }

    public static function getElements(): array
    {
        return [
            'fire' => 'Feu',
            'water' => 'Eau',
            'earth' => 'Terre',
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
            'mythic' => 'Mythique',
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
            $query->where('power_type', $request->armor);
        }

        $cards = $query->with('cardImages')->orderBy('name')->get();

        // Niveaux de fusion de l'utilisateur connecté, indexés par card_id
        $userFusionLevels = [];
        if (auth()->check()) {
            $userFusionLevels = auth()->user()
                ->cards()
                ->pluck('user_cards.fusion_level', 'cards.id')
                ->toArray();
        }

        return view('cards.index', compact('cards', 'userFusionLevels'));
    }

    /**
     * Affiche le formulaire de création (Admin)
     */
    public function create(): View
    {
        $factions = Faction::orderBy('name')->get();
        $attacks = Attack::orderBy('name')->get();
        $powerTypes = self::getPowerTypes();
        $elements = self::getElements();
        $rarities = self::getRarities();

        return view('cards.create', compact(
            'factions',
            'attacks',
            'powerTypes',
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
            'power_type' => 'required|in:' . implode(',', array_keys(self::getPowerTypes())),
            'element' => 'required|in:' . implode(',', array_keys(self::getElements())),
            'rarity' => 'required|in:' . implode(',', array_keys(self::getRarities())),
            'health_points' => 'required|integer|min:1',
            'endurance' => 'required|integer|min:1',
            'defense' => 'required|integer|min:0|max:200',
            'power' => 'required|integer|min:1|max:500',
            'cosmos' => 'required|integer|min:1',
            'cost' => 'required|integer|min:1',
            'passive_ability_name' => 'nullable|string|max:100',
            'passive_ability_description' => 'nullable|string',
            'passive_effect_type' => 'nullable|in:none,heal_allies,shield_self,boost_allies',
            'passive_effect_value' => 'nullable|integer|min:0',
            'main_attack_id' => 'required|exists:attacks,id',
            'secondary_attack_1_id' => 'nullable|exists:attacks,id',
            'secondary_attack_2_id' => 'nullable|exists:attacks,id',
            'images' => 'nullable|array',
            'images.*.primary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*.secondary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $card = Card::create($validated);

        // Sauvegarde des images par niveau de fusion
        for ($level = 1; $level <= 10; $level++) {
            $primaryFile = $request->file("images.{$level}.primary");
            $secondaryFile = $request->file("images.{$level}.secondary");

            if ($primaryFile || $secondaryFile) {
                $card->cardImages()->create([
                    'fusion_level'   => $level,
                    'image_primary'   => $primaryFile?->store('cards', 'public'),
                    'image_secondary' => $secondaryFile?->store('cards', 'public'),
                ]);
            }
        }

        return redirect()->route('cards.index')
            ->with('success', 'Carte créée avec succès.');
    }

    /**
     * Affiche le détail d'une carte
     */
    public function show(Card $card): View
    {
        $card->load('faction', 'mainAttack', 'secondaryAttack1', 'secondaryAttack2', 'cardImages');

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

        $levelImage = $card->imageForLevel($fusionLevel);

        return view('cards.show', compact('card', 'fusionLevel', 'boostedStats', 'owned', 'levelImage'));
    }

    /**
     * Affiche le formulaire d'édition (Admin)
     */
    public function edit(Card $card): View
    {
        $card->load('cardImages');
        $factions = Faction::orderBy('name')->get();
        $attacks = Attack::orderBy('name')->get();
        $powerTypes = self::getPowerTypes();
        $elements = self::getElements();
        $rarities = self::getRarities();
        $cardImagesByLevel = $card->cardImages->keyBy('fusion_level');

        return view('cards.edit', compact(
            'card',
            'factions',
            'attacks',
            'powerTypes',
            'elements',
            'rarities',
            'cardImagesByLevel',
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
            'power_type' => 'required|in:' . implode(',', array_keys(self::getPowerTypes())),
            'element' => 'required|in:' . implode(',', array_keys(self::getElements())),
            'rarity' => 'required|in:' . implode(',', array_keys(self::getRarities())),
            'health_points' => 'required|integer|min:1',
            'endurance' => 'required|integer|min:1',
            'defense' => 'required|integer|min:0|max:200',
            'power' => 'required|integer|min:1|max:500',
            'cosmos' => 'required|integer|min:1',
            'cost' => 'required|integer|min:1',
            'passive_ability_name' => 'nullable|string|max:100',
            'passive_ability_description' => 'nullable|string',
            'passive_effect_type' => 'nullable|in:none,heal_allies,shield_self,boost_allies',
            'passive_effect_value' => 'nullable|integer|min:0',
            'main_attack_id' => 'required|exists:attacks,id',
            'secondary_attack_1_id' => 'nullable|exists:attacks,id',
            'secondary_attack_2_id' => 'nullable|exists:attacks,id',
            'images' => 'nullable|array',
            'images.*.primary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*.secondary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $card->update($validated);

        // Mise à jour des images par niveau de fusion
        $existingImages = $card->cardImages()->get()->keyBy('fusion_level');

        for ($level = 1; $level <= 10; $level++) {
            $primaryFile   = $request->file("images.{$level}.primary");
            $secondaryFile = $request->file("images.{$level}.secondary");

            if (!$primaryFile && !$secondaryFile) {
                continue;
            }

            $existing = $existingImages->get($level);
            $imagePrimary   = $existing?->image_primary;
            $imageSecondary = $existing?->image_secondary;

            if ($primaryFile) {
                if ($imagePrimary) {
                    Storage::disk('public')->delete($imagePrimary);
                }
                $imagePrimary = $primaryFile->store('cards', 'public');
            }

            if ($secondaryFile) {
                if ($imageSecondary) {
                    Storage::disk('public')->delete($imageSecondary);
                }
                $imageSecondary = $secondaryFile->store('cards', 'public');
            }

            $card->cardImages()->updateOrCreate(
                ['fusion_level' => $level],
                ['image_primary' => $imagePrimary, 'image_secondary' => $imageSecondary]
            );
        }

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

        // Suppression des fichiers de toutes les images de niveaux
        foreach ($card->cardImages as $cardImage) {
            if ($cardImage->image_primary) {
                Storage::disk('public')->delete($cardImage->image_primary);
            }
            if ($cardImage->image_secondary) {
                Storage::disk('public')->delete($cardImage->image_secondary);
            }
        }

        $card->delete();

        return redirect()->route('cards.index')
            ->with('success', 'Carte supprimée avec succès.');
    }
}
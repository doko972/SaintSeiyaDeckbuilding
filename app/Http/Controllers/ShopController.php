<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Prix des boosters
     */
    private const BOOSTER_PRICES = [
        'bronze' => 100,
        'silver' => 250,
        'gold' => 500,
        'legendary' => 1000,
    ];

    /**
     * Nombre de cartes par booster
     */
    private const CARDS_PER_BOOSTER = 3;

    /**
     * Page de la boutique
     */
    public function index(): View
    {
        $user = auth()->user();

        $boosters = [
            [
                'id' => 'bronze',
                'name' => 'Booster Bronze',
                'description' => 'Contient 3 cartes avec une chance de cartes communes et rares.',
                'price' => self::BOOSTER_PRICES['bronze'],
                'icon' => '🥉',
                'color' => 'from-orange-600 to-orange-800',
                'rates' => ['Commune: 80%', 'Rare: 18%', 'Épique: 2%'],
            ],
            [
                'id' => 'silver',
                'name' => 'Booster Argent',
                'description' => 'Contient 3 cartes avec une meilleure chance de rares.',
                'price' => self::BOOSTER_PRICES['silver'],
                'icon' => '🥈',
                'color' => 'from-gray-400 to-gray-600',
                'rates' => ['Commune: 50%', 'Rare: 40%', 'Épique: 9.5%', 'Légendaire: 0.5%'],
            ],
            [
                'id' => 'gold',
                'name' => 'Booster Or',
                'description' => 'Contient 3 cartes avec une chance de légendaires !',
                'price' => self::BOOSTER_PRICES['gold'],
                'icon' => '🥇',
                'color' => 'from-yellow-500 to-yellow-700',
                'rates' => ['Rare: 55%', 'Épique: 42%', 'Légendaire: 3%'],
            ],
            [
                'id' => 'legendary',
                'name' => 'Booster Légendaire',
                'description' => 'Contient 3 cartes avec une légendaire garantie !',
                'price' => self::BOOSTER_PRICES['legendary'],
                'icon' => '👑',
                'color' => 'from-purple-600 to-pink-600',
                'rates' => ['Rare: 20%', 'Épique: 69%', 'Légendaire: 10%', 'Mythique: 1%', '1 Légendaire garantie'],
            ],
        ];

        return view('shop.index', compact('user', 'boosters'));
    }

    /**
     * Acheter un booster
     */
    public function buyBooster(Request $request, string $type): RedirectResponse
    {
        $user = auth()->user();

        // Vérifier le type de booster
        if (!isset(self::BOOSTER_PRICES[$type])) {
            return back()->with('error', 'Type de booster invalide.');
        }

        $price = self::BOOSTER_PRICES[$type];

        // Vérifier les fonds
        if ($user->coins < $price) {
            return back()->with('error', 'Pas assez de pièces ! Il vous faut ' . $price . ' pièces.');
        }

        // Dépenser les pièces
        $user->spendCoins($price);

        // Générer les cartes
        $cards = $this->generateBoosterCards($type);

        // Ajouter les cartes à la collection
        foreach ($cards as $card) {
            $user->addCard($card);
        }

        // Préparer le message
        $cardNames = $cards->pluck('name')->implode(', ');

        return redirect()->route('shop.result')
            ->with('booster_result', [
                'type' => $type,
                'cards' => $cards,
            ]);
    }

/**
 * Affiche le résultat de l'ouverture
 */
public function result(): View|RedirectResponse
{
    $result = session('booster_result');

    if (!$result) {
        return redirect()->route('shop.index');
    }

    return view('shop.result', ['result' => $result]);
}

    /**
     * Génère les cartes d'un booster
     */
    private function generateBoosterCards(string $type): \Illuminate\Support\Collection
    {
        $cards = collect();

        for ($i = 0; $i < self::CARDS_PER_BOOSTER; $i++) {
            $rarity = $this->rollRarity($type, $i);
            $card = Card::where('rarity', $rarity)->inRandomOrder()->first();
            
            // Fallback si aucune carte de cette rareté
            if (!$card) {
                $card = Card::inRandomOrder()->first();
            }

            if ($card) {
                $cards->push($card);
            }
        }

        return $cards;
    }

    /**
     * Détermine la rareté d'une carte selon le type de booster
     */
    private function rollRarity(string $type, int $cardIndex): string
    {
        $roll = rand(1, 1000); // Plus de précision pour les faibles pourcentages

        switch ($type) {
            case 'bronze':
                // 80% common, 18% rare, 2% epic
                if ($roll <= 800) return 'common';
                if ($roll <= 980) return 'rare';
                return 'epic';

            case 'silver':
                // 50% common, 40% rare, 9.5% epic, 0.5% legendary
                if ($roll <= 500) return 'common';
                if ($roll <= 900) return 'rare';
                if ($roll <= 995) return 'epic';
                return 'legendary';

            case 'gold':
                // 55% rare, 42% epic, 3% legendary
                if ($roll <= 550) return 'rare';
                if ($roll <= 970) return 'epic';
                return 'legendary';

            case 'legendary':
                // Première carte toujours légendaire
                if ($cardIndex === 0) return 'legendary';
                // 20% rare, 69% epic, 10% legendary, 1% mythic
                if ($roll <= 200) return 'rare';
                if ($roll <= 890) return 'epic';
                if ($roll <= 990) return 'legendary';
                return 'mythic';

            default:
                return 'common';
        }
    }
}
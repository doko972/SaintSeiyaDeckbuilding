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
                'rates' => ['Commune: 70%', 'Rare: 25%', 'Épique: 5%'],
            ],
            [
                'id' => 'silver',
                'name' => 'Booster Argent',
                'description' => 'Contient 3 cartes avec une meilleure chance de rares.',
                'price' => self::BOOSTER_PRICES['silver'],
                'icon' => '🥈',
                'color' => 'from-gray-400 to-gray-600',
                'rates' => ['Commune: 40%', 'Rare: 45%', 'Épique: 15%'],
            ],
            [
                'id' => 'gold',
                'name' => 'Booster Or',
                'description' => 'Contient 3 cartes avec une chance de légendaires !',
                'price' => self::BOOSTER_PRICES['gold'],
                'icon' => '🥇',
                'color' => 'from-yellow-500 to-yellow-700',
                'rates' => ['Rare: 50%', 'Épique: 40%', 'Légendaire: 10%'],
            ],
            [
                'id' => 'legendary',
                'name' => 'Booster Légendaire',
                'description' => 'Contient 3 cartes avec une légendaire garantie !',
                'price' => self::BOOSTER_PRICES['legendary'],
                'icon' => '👑',
                'color' => 'from-purple-600 to-pink-600',
                'rates' => ['Épique: 60%', 'Légendaire: 40%', '1 Légendaire garantie'],
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
        $roll = rand(1, 100);

        switch ($type) {
            case 'bronze':
                if ($roll <= 70) return 'common';
                if ($roll <= 95) return 'rare';
                return 'epic';

            case 'silver':
                if ($roll <= 40) return 'common';
                if ($roll <= 85) return 'rare';
                return 'epic';

            case 'gold':
                if ($roll <= 50) return 'rare';
                if ($roll <= 90) return 'epic';
                return 'legendary';

            case 'legendary':
                // Première carte toujours légendaire
                if ($cardIndex === 0) return 'legendary';
                if ($roll <= 60) return 'epic';
                return 'legendary';

            default:
                return 'common';
        }
    }
}
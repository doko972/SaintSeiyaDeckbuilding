<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FirstDrawController extends Controller
{
    private const FIRST_DRAW_COUNT = 7;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la page du premier tirage gratuit
     */
    public function index()
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur a bien sélectionné son starter
        if (!$user->has_selected_starter) {
            return redirect()->route('starter-pack.index');
        }

        // Vérifier que l'utilisateur n'a pas déjà fait son premier tirage
        if ($user->has_completed_first_draw) {
            return redirect()->route('dashboard')
                ->with('info', 'Vous avez déjà effectué votre premier tirage gratuit.');
        }

        return view('first-draw.index', [
            'drawCount' => self::FIRST_DRAW_COUNT,
        ]);
    }

    /**
     * Effectue le premier tirage gratuit de 7 cartes common
     */
    public function draw(Request $request)
    {
        $user = Auth::user();

        // Vérifications
        if (!$user->has_selected_starter) {
            return redirect()->route('starter-pack.index');
        }

        if ($user->has_completed_first_draw) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà effectué votre premier tirage gratuit.');
        }

        // Récupérer les cartes common disponibles
        $commonCards = Card::where('rarity', 'common')->get();

        // Si pas de cartes common, prendre les cartes rare de faible coût comme fallback
        if ($commonCards->isEmpty()) {
            $commonCards = Card::where('rarity', 'rare')
                ->where('cost', '<=', 4)
                ->get();
        }

        // Si toujours pas de cartes, erreur
        if ($commonCards->isEmpty()) {
            return back()->with('error', 'Aucune carte de base disponible pour le tirage.');
        }

        DB::beginTransaction();

        try {
            $drawnCards = [];

            // Tirer 7 cartes aléatoires (avec possibilité de doublons)
            for ($i = 0; $i < self::FIRST_DRAW_COUNT; $i++) {
                $card = $commonCards->random();
                $user->addCard($card, 1);

                // Compter les occurrences pour l'affichage
                if (!isset($drawnCards[$card->id])) {
                    $drawnCards[$card->id] = [
                        'card' => $card,
                        'quantity' => 0,
                    ];
                }
                $drawnCards[$card->id]['quantity']++;
            }

            // Marquer le premier tirage comme effectué
            $user->update(['has_completed_first_draw' => true]);

            DB::commit();

            // Stocker les cartes tirées en session pour l'affichage
            session(['first_draw_cards' => $drawnCards]);

            return redirect()->route('first-draw.result');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du tirage.');
        }
    }

    /**
     * Affiche le résultat du premier tirage
     */
    public function result()
    {
        $drawnCards = session('first_draw_cards');

        if (!$drawnCards) {
            return redirect()->route('dashboard');
        }

        // Charger les relations pour l'affichage
        $cardIds = array_keys($drawnCards);
        $cards = Card::with(['faction', 'mainAttack'])
            ->whereIn('id', $cardIds)
            ->get()
            ->keyBy('id');

        // Mettre à jour avec les données complètes
        foreach ($drawnCards as $cardId => &$item) {
            $item['card'] = $cards[$cardId];
        }

        // Nettoyer la session
        session()->forget('first_draw_cards');

        return view('first-draw.result', [
            'drawnCards' => $drawnCards,
            'totalCards' => self::FIRST_DRAW_COUNT,
        ]);
    }
}

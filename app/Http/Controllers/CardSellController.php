<?php

namespace App\Http\Controllers;

use App\Services\CardSellService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardSellController extends Controller
{
    protected CardSellService $cardSellService;

    public function __construct(CardSellService $cardSellService)
    {
        $this->cardSellService = $cardSellService;
    }

    /**
     * Page de vente - liste les cartes vendables
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Récupérer toutes les cartes du joueur
        $sellableCards = $user->cards()
            ->with(['faction', 'mainAttack'])
            ->orderBy('rarity', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('sell.index', [
            'sellableCards' => $sellableCards,
            'userCoins' => $user->coins,
            'sellPrices' => CardSellService::SELL_PRICES,
        ]);
    }

    /**
     * Aperçu d'une vente (AJAX)
     */
    public function preview(Request $request): JsonResponse
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $preview = $this->cardSellService->getSellPreview($user, $request->card_id);

        if (isset($preview['error'])) {
            return response()->json($preview, 404);
        }

        return response()->json($preview);
    }

    /**
     * Exécuter la vente
     */
    public function sell(Request $request): JsonResponse
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'quantity' => 'required|integer|min:1',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $result = $this->cardSellService->sellCard(
            $user,
            $request->card_id,
            $request->quantity
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['reason'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Vente réussie ! +{$result['coins_earned']} pièces",
            'coins_earned' => $result['coins_earned'],
            'new_balance' => $result['new_balance'],
            'card_name' => $result['card_name'],
            'quantity_sold' => $result['quantity_sold'],
        ]);
    }
}

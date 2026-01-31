<?php

namespace App\Http\Controllers;

use App\Services\StarterPackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StarterPackController extends Controller
{
    protected $starterPackService;

    public function __construct(StarterPackService $starterPackService)
    {
        $this->middleware('auth');
        $this->starterPackService = $starterPackService;
    }

    /**
     * Affiche la page de sélection du Bronze de départ
     */
    public function index()
    {
        $user = Auth::user();

        // Rediriger si l'utilisateur a déjà sélectionné son Bronze
        if ($user->has_selected_starter) {
            return redirect()->route('dashboard')->with('info', 'Vous avez déjà reçu votre starter pack.');
        }

        // Récupérer les Bronzes disponibles
        $bronzeCards = $this->starterPackService->getAvailableBronzeStarters();
        
        // Récupérer les détails du starter pack
        $starterDetails = $this->starterPackService->getStarterPackDetails();

        return view('starter-pack.select-bronze', compact('bronzeCards', 'starterDetails'));
    }

    /**
     * Traite la sélection du Bronze et distribue le starter pack
     */
    public function selectBronze(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a déjà sélectionné son Bronze
        if ($user->has_selected_starter) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà reçu votre starter pack.');
        }

        // Valider la requête
        $request->validate([
            'bronze_card_id' => 'required|integer|exists:cards,id'
        ]);

        // Distribuer le starter pack
        $success = $this->starterPackService->distributeStarterPack($user, $request->bronze_card_id);

        if ($success) {
            // Rediriger vers le premier tirage gratuit
            return redirect()->route('first-draw.index')->with('success', 'Starter pack recu ! Effectuez maintenant votre premier tirage gratuit.');
        }

        return back()->with('error', 'Une erreur est survenue lors de la distribution du starter pack.');
    }

    /**
     * Affiche les détails du starter pack (pour information)
     */
    public function details()
    {
        $starterDetails = $this->starterPackService->getStarterPackDetails();
        $bronzeCards = $this->starterPackService->getAvailableBronzeStarters();

        return view('starter-pack.details', compact('starterDetails', 'bronzeCards'));
    }
}
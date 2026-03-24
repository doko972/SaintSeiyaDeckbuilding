<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pvp');

        $pvpTop = User::where('has_selected_starter', true)
            ->where('pvp_wins', '>', 0)
            ->orderByDesc('pvp_wins')
            ->orderByDesc('wins')
            ->limit(20)
            ->get(['id', 'name', 'pvp_wins', 'wins', 'losses', 'current_rank']);

        $pveTop = User::where('has_selected_starter', true)
            ->where('pve_wins', '>', 0)
            ->orderByDesc('pve_wins')
            ->orderByDesc('wins')
            ->limit(20)
            ->get(['id', 'name', 'pve_wins', 'wins', 'losses', 'current_rank']);

        $user = auth()->user();

        // Position du joueur connecté dans chaque classement
        $userPvpRank = $pvpTop->search(fn($u) => $u->id === $user->id);
        $userPveRank = $pveTop->search(fn($u) => $u->id === $user->id);

        // Score du leader pour calcul du pourcentage
        $pvpLeaderScore = $pvpTop->first()?->pvp_wins ?? 1;
        $pveLeaderScore = $pveTop->first()?->pve_wins ?? 1;

        return view('leaderboard.index', compact(
            'pvpTop', 'pveTop', 'tab',
            'userPvpRank', 'userPveRank',
            'pvpLeaderScore', 'pveLeaderScore'
        ));
    }
}

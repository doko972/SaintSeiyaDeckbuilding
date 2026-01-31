<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SingleSession
{
    /**
     * Vérifie que l'utilisateur utilise sa session active.
     * Si une autre session a été ouverte ailleurs, déconnecte cette session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();

            // Si la session stockée ne correspond pas à la session actuelle
            if ($user->current_session_id && $user->current_session_id !== $currentSessionId) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Votre session a été fermée car une connexion a été effectuée depuis un autre appareil.');
            }
        }

        return $next($request);
    }
}

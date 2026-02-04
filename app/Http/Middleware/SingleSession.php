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
     * Si une autre session a été ouverte depuis un autre appareil, déconnecte cette session.
     * Si c'est le même appareil (même IP), on met à jour la session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();
            $currentIp = $request->ip();

            // Si pas de session stockée, on l'enregistre
            if (!$user->current_session_id) {
                $user->update([
                    'current_session_id' => $currentSessionId,
                    'last_session_ip' => $currentIp,
                ]);
                return $next($request);
            }

            // Si la session correspond, tout va bien
            if ($user->current_session_id === $currentSessionId) {
                return $next($request);
            }

            // La session a changé - vérifier si c'est le même appareil (même IP)
            if ($user->last_session_ip === $currentIp) {
                // Même IP = probablement le même appareil, session régénérée
                // On met à jour silencieusement
                $user->update([
                    'current_session_id' => $currentSessionId,
                ]);
                return $next($request);
            }

            // IP différente = connexion depuis un autre appareil
            // Déconnecter cette session
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Pour les requêtes AJAX/API, retourner du JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Votre session a été fermée car une connexion a été effectuée depuis un autre appareil.',
                    'redirect' => route('login')
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Votre session a été fermée car une connexion a été effectuée depuis un autre appareil.');
        }

        return $next($request);
    }
}

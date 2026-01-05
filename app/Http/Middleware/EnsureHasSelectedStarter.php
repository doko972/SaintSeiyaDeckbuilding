<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasSelectedStarter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est connecté et n'a pas encore sélectionné son Bronze
        if ($user && !$user->has_selected_starter) {
            // Exception : ne pas bloquer la route de sélection du Bronze elle-même
            if (!$request->routeIs('starter-pack.*') && !$request->routeIs('logout')) {
                return redirect()->route('starter-pack.index')
                    ->with('info', 'Veuillez sélectionner votre Chevalier de Bronze de départ.');
            }
        }

        return $next($request);
    }
}
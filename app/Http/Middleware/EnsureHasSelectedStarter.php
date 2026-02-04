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
                // Retourner JSON pour les requêtes API/AJAX
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['error' => 'Starter pack non sélectionné', 'redirect' => route('starter-pack.index')], 403);
                }
                return redirect()->route('starter-pack.index')
                    ->with('info', 'Veuillez sélectionner votre Chevalier de Bronze de départ.');
            }
        }

        // Si l'utilisateur a sélectionné son starter mais n'a pas fait son premier tirage
        if ($user && $user->has_selected_starter && !$user->has_completed_first_draw) {
            // Exception : ne pas bloquer les routes du premier tirage
            if (!$request->routeIs('first-draw.*') && !$request->routeIs('logout')) {
                // Retourner JSON pour les requêtes API/AJAX
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['error' => 'Premier tirage non effectué', 'redirect' => route('first-draw.index')], 403);
                }
                return redirect()->route('first-draw.index')
                    ->with('info', 'Effectuez votre premier tirage gratuit de 7 cartes !');
            }
        }

        return $next($request);
    }
}
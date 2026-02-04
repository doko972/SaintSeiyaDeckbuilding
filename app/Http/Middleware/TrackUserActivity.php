<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            try {
                // Met a jour seulement si la derniere activite date de plus de 30 secondes
                // pour eviter les updates trop frequentes
                $user = auth()->user();
                $lastActivity = $user->last_activity_at;

                if (!$lastActivity || $lastActivity->diffInSeconds(now()) >= 30) {
                    $user->update(['last_activity_at' => now()]);
                }
            } catch (\Exception $e) {
                // Ne pas bloquer la requete si la mise a jour echoue
                \Log::warning('TrackUserActivity error: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}

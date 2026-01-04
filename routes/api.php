<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\FactionController;
use App\Http\Controllers\Api\AttackController;
use App\Http\Controllers\Api\DeckController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GameApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API publiques
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    
    // Cartes
    Route::get('/cards', [CardController::class, 'index']);
    Route::get('/cards/random', [CardController::class, 'random']);
    Route::get('/cards/{card}', [CardController::class, 'show']);

    // Factions
    Route::get('/factions', [FactionController::class, 'index']);
    Route::get('/factions/{faction}', [FactionController::class, 'show']);

    // Attaques
    Route::get('/attacks', [AttackController::class, 'index']);
    Route::get('/attacks/{attack}', [AttackController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Routes API authentifiées
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Decks de l'utilisateur
    Route::get('/decks', [DeckController::class, 'index']);
    Route::get('/decks/active', [DeckController::class, 'active']);
    Route::get('/decks/{deck}', [DeckController::class, 'show']);

    // Jeu / Combat
    Route::post('/game/init', [GameController::class, 'initBattle']);
    Route::post('/game/play-card', [GameController::class, 'playCard']);
    Route::post('/game/attack', [GameController::class, 'attack']);
    Route::post('/game/end-turn', [GameController::class, 'endTurn']);

    Route::post('/game/claim-reward', [GameController::class, 'claimReward']);
});

// Routes de combat (protégées par auth:sanctum ou web)
Route::prefix('v1/game')->middleware('web')->group(function () {
    Route::post('/init-battle', [GameApiController::class, 'initBattle']);
    Route::post('/play-card', [GameApiController::class, 'playCard']);
    Route::post('/attack', [GameApiController::class, 'attack']);
    Route::post('/end-turn', [GameApiController::class, 'endTurn']);
    Route::post('/claim-reward', [GameApiController::class, 'claimReward']);
});
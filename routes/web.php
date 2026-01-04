<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FactionController;
use App\Http\Controllers\AttackController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\GameViewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\PvpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Routes authentifiées
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cartes (lecture seule pour tous)
    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');

    // Factions (lecture seule pour tous)
    Route::get('/factions', [FactionController::class, 'index'])->name('factions.index');
    Route::get('/factions/{faction}', [FactionController::class, 'show'])->name('factions.show');

    // Attaques (lecture seule pour tous)
    Route::get('/attacks', [AttackController::class, 'index'])->name('attacks.index');
    Route::get('/attacks/{attack}', [AttackController::class, 'show'])->name('attacks.show');

    // Decks (CRUD pour l'utilisateur connecté)
    Route::resource('decks', DeckController::class);

    // Jeu
    Route::get('/game', [GameViewController::class, 'index'])->name('game.index');
    Route::get('/game/battle/{deck}', [GameViewController::class, 'battle'])->name('game.battle');

    // Boutique
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/shop/buy/{type}', [ShopController::class, 'buyBooster'])->name('shop.buy');
    Route::get('/shop/result', [ShopController::class, 'result'])->name('shop.result');

    // Collection
    Route::get('/collection', [CollectionController::class, 'index'])->name('collection.index');
    Route::get('/collection/{card}', [CollectionController::class, 'show'])->name('collection.show');
});

/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Gestion des Factions
    Route::get('/factions/create', [FactionController::class, 'create'])->name('factions.create');
    Route::post('/factions', [FactionController::class, 'store'])->name('factions.store');
    Route::get('/factions/{faction}/edit', [FactionController::class, 'edit'])->name('factions.edit');
    Route::put('/factions/{faction}', [FactionController::class, 'update'])->name('factions.update');
    Route::delete('/factions/{faction}', [FactionController::class, 'destroy'])->name('factions.destroy');

    // Gestion des Attaques
    Route::get('/attacks/create', [AttackController::class, 'create'])->name('attacks.create');
    Route::post('/attacks', [AttackController::class, 'store'])->name('attacks.store');
    Route::get('/attacks/{attack}/edit', [AttackController::class, 'edit'])->name('attacks.edit');
    Route::put('/attacks/{attack}', [AttackController::class, 'update'])->name('attacks.update');
    Route::delete('/attacks/{attack}', [AttackController::class, 'destroy'])->name('attacks.destroy');

    // Gestion des Cartes
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('cards.edit');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
});



// Routes PvP
Route::middleware('auth')->prefix('pvp')->name('pvp.')->group(function () {
    Route::get('/lobby', [PvpController::class, 'lobby'])->name('lobby');
    Route::post('/create', [PvpController::class, 'create'])->name('create');
    Route::post('/join/{battle}', [PvpController::class, 'join'])->name('join');
    Route::get('/waiting/{battle}', [PvpController::class, 'waiting'])->name('waiting');
    Route::post('/cancel/{battle}', [PvpController::class, 'cancel'])->name('cancel');
    Route::get('/battle/{battle}', [PvpController::class, 'battle'])->name('battle');
});

require __DIR__.'/auth.php';
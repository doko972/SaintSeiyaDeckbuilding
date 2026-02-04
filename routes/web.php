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
use App\Http\Controllers\Api\PvpApiController;
use App\Http\Controllers\StarterPackController;
use App\Http\Controllers\FirstDrawController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\FusionController;
use App\Http\Controllers\CardSellController;
use App\Http\Controllers\DailyBonusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Routes Starter Pack (accessibles sans avoir sélectionné le Bronze)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('starter-pack')->name('starter-pack.')->group(function () {
    Route::get('/', [StarterPackController::class, 'index'])->name('index');
    Route::post('/select', [StarterPackController::class, 'selectBronze'])->name('select');
    Route::get('/details', [StarterPackController::class, 'details'])->name('details');
});

/*
|--------------------------------------------------------------------------
| Routes Premier Tirage Gratuit (après le starter pack)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('first-draw')->name('first-draw.')->group(function () {
    Route::get('/', [FirstDrawController::class, 'index'])->name('index');
    Route::post('/draw', [FirstDrawController::class, 'draw'])->name('draw');
    Route::get('/result', [FirstDrawController::class, 'result'])->name('result');
});

/*
|--------------------------------------------------------------------------
| Routes authentifiées (nécessitent d'avoir sélectionné le Bronze)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'ensure.starter'])->group(function () {

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

    // Fusion de cartes
    Route::prefix('fusion')->name('fusion.')->group(function () {
        Route::get('/', [FusionController::class, 'index'])->name('index');
        Route::post('/preview', [FusionController::class, 'preview'])->name('preview');
        Route::post('/fuse', [FusionController::class, 'fuse'])->name('fuse');
    });

    // Vente de cartes
    Route::prefix('sell')->name('sell.')->group(function () {
        Route::get('/', [CardSellController::class, 'index'])->name('index');
        Route::post('/preview', [CardSellController::class, 'preview'])->name('preview');
        Route::post('/sell', [CardSellController::class, 'sell'])->name('sell');
    });

    // Bonus quotidien
    Route::prefix('daily-bonus')->name('daily-bonus.')->group(function () {
        Route::get('/check', [DailyBonusController::class, 'check'])->name('check');
        Route::post('/claim', [DailyBonusController::class, 'claim'])->name('claim');
    });

    // Routes PvP
    Route::prefix('pvp')->name('pvp.')->group(function () {
        Route::get('/lobby', [PvpController::class, 'lobby'])->name('lobby');
        Route::post('/create', [PvpController::class, 'create'])->name('create');
        Route::post('/join/{battle}', [PvpController::class, 'join'])->name('join');
        Route::get('/waiting/{battle}', [PvpController::class, 'waiting'])->name('waiting');
        Route::post('/cancel/{battle}', [PvpController::class, 'cancel'])->name('cancel');
        Route::post('/forfeit/{battle}', [PvpController::class, 'forfeit'])->name('forfeit');
        Route::get('/battle/{battle}', [PvpController::class, 'battle'])->name('battle');
    });

    // Routes API PvP (dans web.php pour une bonne gestion des sessions)
    Route::prefix('api/v1/pvp')->group(function () {
        Route::get('/waiting-battles', [PvpApiController::class, 'getWaitingBattles']);
        Route::get('/battle-state/{battle}', [PvpApiController::class, 'getBattleState']);
        Route::post('/play-card', [PvpApiController::class, 'playCard']);
        Route::post('/attack', [PvpApiController::class, 'attack']);
        Route::post('/end-turn', [PvpApiController::class, 'endTurn']);
    });
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

    // Gestion des Musiques
    Route::get('/musics', [MusicController::class, 'index'])->name('musics.index');
    Route::get('/musics/create', [MusicController::class, 'create'])->name('musics.create');
    Route::post('/musics', [MusicController::class, 'store'])->name('musics.store');
    Route::get('/musics/{music}/edit', [MusicController::class, 'edit'])->name('musics.edit');
    Route::put('/musics/{music}', [MusicController::class, 'update'])->name('musics.update');
    Route::delete('/musics/{music}', [MusicController::class, 'destroy'])->name('musics.destroy');
    Route::patch('/musics/{music}/toggle', [MusicController::class, 'toggle'])->name('musics.toggle');

    // Gestion des Combos
    Route::get('/combos', [ComboController::class, 'index'])->name('combos.index');
    Route::get('/combos/create', [ComboController::class, 'create'])->name('combos.create');
    Route::post('/combos', [ComboController::class, 'store'])->name('combos.store');
    Route::get('/combos/{combo}/edit', [ComboController::class, 'edit'])->name('combos.edit');
    Route::put('/combos/{combo}', [ComboController::class, 'update'])->name('combos.update');
    Route::delete('/combos/{combo}', [ComboController::class, 'destroy'])->name('combos.destroy');
    Route::patch('/combos/{combo}/toggle', [ComboController::class, 'toggle'])->name('combos.toggle');
});

require __DIR__ . '/auth.php';
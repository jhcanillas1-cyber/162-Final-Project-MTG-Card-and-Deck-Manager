<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScryfallCardController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DeckController;

// Home (welcome) page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (only accessible to logged-in + verified users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cards page 
    Route::get('/cards', [ScryfallCardController::class, 'index'])->name('cards.index');
    Route::post('/cards/import', [ScryfallCardController::class, 'import'])->name('cards.import');

    // Collections pages
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections/add-card', [CollectionController::class, 'addCard'])->name('collections.addCard');
    Route::post('/collections/remove-card', [CollectionController::class, 'removeCard'])->name('collections.removeCard');

    // Deck pages
    Route::get('/decks', [DeckController::class, 'index'])->name('decks.index');
    Route::post('/decks', [DeckController::class, 'store'])->name('decks.store');
    Route::get('/decks/{id}', [DeckController::class, 'show'])->name('decks.show');
    Route::post('/decks/add-card', [DeckController::class, 'addCard'])->name('decks.addCard');
    Route::post('/decks/remove-card', [DeckController::class, 'removeCard'])->name('decks.removeCard');
    Route::get('/decks/{deck}/export', [DeckController::class, 'export'])->name('decks.export');
});

// Include default Breeze auth routes
require __DIR__.'/auth.php';



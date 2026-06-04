<?php

use App\Events\TestMessageSent;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * ℹ️ Laravel Reverb
 * ===============
 * Cette route sert seulement à déclencher un test.
 * 
 * Laravel fait deux choses :
 *  1. Il envoie l’événement TestMessageSent
 *  2. Il retourne "Event envoyé" dans le navigateur
 * 
 * Le texte "Event envoyé" est pour le navigateur.
 * Le texte "Bonjour depuis Laravel Reverb" est envoyé vers React Native par WebSocket.
 */
Route::get('/broadcast-test', function () {
    TestMessageSent::dispatch(
        'Bonjour depuis Laravel Reverb'
    );

    return 'Event envoyé';
});

require __DIR__ . '/auth.php';

<?php

use App\Events\TestMessageSent;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsurePlatformRoles;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return app(AdminController::class)->show();
})->middleware(['auth', 'verified', EnsurePlatformRoles::class])->name('dashboard');

Route::get('/dashboard', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function (): void {
    Route::get('dashboard-data', [AdminController::class, 'dashboard'])->name('admin.dashboard-data');
    Route::get('notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
    Route::get('account', [AdminController::class, 'account'])->name('admin.account');
    Route::put('account', [AdminController::class, 'updateAccount'])->name('admin.account.update');
    Route::put('account/websites', [AdminController::class, 'updateWebsites'])->name('admin.account.websites.update');
    Route::put('account/password', [AdminController::class, 'updateAccountPassword'])->name('admin.account.password.update');
    Route::post('account/avatar', [AdminController::class, 'updateAvatar'])->name('admin.account.avatar.update');
    Route::delete('account', [AdminController::class, 'destroyAccount'])->name('admin.account.destroy');
    Route::get('search', [AdminController::class, 'search'])->name('admin.search');
});

Route::get('/{adminPage}', [AdminController::class, 'show'])
    ->whereIn('adminPage', ['role', 'pricing', 'payment', 'reason', 'field', 'level', 'competence', 'medal', 'user', 'clash', 'report', 'notifications', 'account'])
    ->middleware(['auth', 'verified', EnsurePlatformRoles::class]);

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

require __DIR__.'/auth.php';

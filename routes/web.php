<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\HomeController;

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [TalentController::class, 'index'])->name('talent.search');
Route::get('/profile/{username}', [TalentController::class, 'profile'])->name('talent.profile');

// Favorites routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::prefix('api')->group(function () {
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('api.favorites.store');
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('api.favorites.destroy');
        Route::get('/favorites/check/{id}', [FavoriteController::class, 'check'])->name('api.favorites.check');
    });
    
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});

// Opportunity routes
Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.search');
Route::get('/opportunities/{id}', [OpportunityController::class, 'show'])->name('opportunities.show');

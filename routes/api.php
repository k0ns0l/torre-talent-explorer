<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ConnectivityController;
use App\Http\Controllers\FavoriteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Search routes
Route::post('/search', [TalentController::class, 'search']);
Route::post('/search/featured', [TalentController::class, 'featuredProfiles']);
Route::get('/profile/{username}', [TalentController::class, 'profileApi']);

// Opportunities routes
Route::post('/opportunities/search', [OpportunityController::class, 'search']);
Route::post('/opportunities/featured', [OpportunityController::class, 'featuredOpportunities']);

// Analytics routes
Route::post('/analytics/skills', [AnalyticsController::class, 'skillAnalysis']);
Route::post('/analytics/compare', [AnalyticsController::class, 'compareProfiles']);

// Export routes
Route::post('/export/profiles', [ExportController::class, 'exportProfiles']);

// Connectivity routes
Route::post('/connectivity/analyze', [ConnectivityController::class, 'analyzeConnectivity']);
<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\Api\LinkTypeController;
use App\Http\Controllers\Api\UserSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Link Types
    Route::get('link-types', [LinkTypeController::class, 'index']);
    Route::apiResource('link-types', LinkTypeController::class)->except(['index']);
    
    // Links
    Route::apiResource('links', LinkController::class);
    Route::post('links/{link}/favorite', [LinkController::class, 'toggleFavorite']);
    Route::post('links/{link}/click', [LinkController::class, 'incrementClick']);
    Route::get('links/search', [LinkController::class, 'search']);
    
    // User Settings
    Route::get('user-settings', [UserSettingsController::class, 'show']);
    Route::put('user-settings', [UserSettingsController::class, 'update']);
    
    // Utility endpoints
    Route::post('scrape-meta', [LinkController::class, 'scrapeMeta']);
});

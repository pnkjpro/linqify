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
    Route::apiResource('categories', CategoryController::class)->names([
        'index' => 'api.categories.index',
        'store' => 'api.categories.store',
        'show' => 'api.categories.show',
        'update' => 'api.categories.update',
        'destroy' => 'api.categories.destroy',
    ]);
    
    // Link Types
    Route::get('link-types', [LinkTypeController::class, 'index'])->name('api.link-types.index');
    Route::apiResource('link-types', LinkTypeController::class)->except(['index'])->names([
        'store' => 'api.link-types.store',
        'show' => 'api.link-types.show',
        'update' => 'api.link-types.update',
        'destroy' => 'api.link-types.destroy',
    ]);
    
    // Links
    Route::apiResource('links', LinkController::class)->names([
        'index' => 'api.links.index',
        'store' => 'api.links.store',
        'show' => 'api.links.show',
        'update' => 'api.links.update',
        'destroy' => 'api.links.destroy',
    ]);
    Route::post('links/{link}/favorite', [LinkController::class, 'toggleFavorite'])->name('api.links.favorite');
    Route::post('links/{link}/click', [LinkController::class, 'incrementClick'])->name('api.links.click');
    Route::get('links/search', [LinkController::class, 'search'])->name('api.links.search');
    
    // User Settings
    Route::get('user-settings', [UserSettingsController::class, 'show'])->name('api.user-settings.show');
    Route::put('user-settings', [UserSettingsController::class, 'update'])->name('api.user-settings.update');
    
    // Utility endpoints
    Route::post('scrape-meta', [LinkController::class, 'scrapeMeta'])->name('api.scrape-meta');
});

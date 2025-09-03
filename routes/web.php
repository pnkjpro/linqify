<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinksController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Links Management
    Route::get('/links', [LinksController::class, 'index'])->name('links.index');
    Route::get('/links/create', [LinksController::class, 'create'])->name('links.create');
    Route::get('/links/{link}/edit', [LinksController::class, 'edit'])->name('links.edit');
    
    // Categories Management
    Route::get('/categories', [LinksController::class, 'categories'])->name('categories.index');
    
    // Settings
    Route::get('/settings', [LinksController::class, 'settings'])->name('settings');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

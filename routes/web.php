<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Main dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Provider routes
Route::middleware(['auth', 'provider', 'active.membership'])->group(function () {
    Route::get('/provider/dashboard', [DashboardController::class, 'index'])->name('provider.dashboard');
    Route::resource('food-items', FoodItemController::class);
    Route::get('/provider/onboarding', function () {
        return view('provider.onboarding');
    })->name('provider.onboarding');
});

// Customer routes
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/browse', [BrowseController::class, 'index'])->name('browse.index');
    Route::get('/browse/{foodItem}', [BrowseController::class, 'show'])->name('browse.show');
    Route::get('/providers', [BrowseController::class, 'providers'])->name('browse.providers');
    Route::get('/providers/{provider}', [BrowseController::class, 'provider'])->name('browse.provider');
});

// Order routes (accessible by both customers and providers)
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
});

// Membership routes
Route::middleware('auth')->group(function () {
    Route::get('/membership/expired', function () {
        return view('membership.expired');
    })->name('membership.expired');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

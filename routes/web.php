<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\SearchController;
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
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/{foodItem}', [SearchController::class, 'show'])->name('search.show');
    Route::get('/search/providers', [SearchController::class, 'providers'])->name('search.providers');
    Route::get('/search/providers/{provider}', [SearchController::class, 'provider'])->name('search.provider');
});

// Public food item detail route
Route::get('/food-items/{foodItem}', [\App\Http\Controllers\FoodItemController::class, 'show'])->name('food-items.show');

// Order routes (accessible by both customers and providers)
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/accept', [\App\Http\Controllers\OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [\App\Http\Controllers\OrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/preparing', [\App\Http\Controllers\OrderController::class, 'preparing'])->name('orders.preparing');
    Route::post('/orders/{order}/ready', [\App\Http\Controllers\OrderController::class, 'ready'])->name('orders.ready');
    Route::post('/orders/{order}/collected', [\App\Http\Controllers\OrderController::class, 'collected'])->name('orders.collected');
    Route::post('/orders/{order}/completed', [\App\Http\Controllers\OrderController::class, 'completed'])->name('orders.completed');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
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

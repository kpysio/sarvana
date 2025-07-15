<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Auth\ProviderRegisterController;

// Auth routes (login, register, etc.) - must be loaded first and outside any group
require __DIR__.'/auth.php';

// Universal dashboard alias for backward compatibility
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect('/login');
    if ($user->user_type === 'provider') return redirect('/provider/dashboard');
    if ($user->user_type === 'customer') return redirect('/customer/dashboard');
    if ($user->user_type === 'admin') return redirect('/admin/dashboard');
    return '/';
})->middleware('auth')->name('dashboard');

// ------------------- ADMIN ROUTES -------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/sales', [ReportsController::class, 'sales'])->name('sales');
        Route::get('/users', [ReportsController::class, 'users'])->name('users');
        Route::get('/providers', [ReportsController::class, 'providers'])->name('providers');
        Route::get('/orders', [ReportsController::class, 'orders'])->name('orders');
    });
    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/approve', [UserController::class, 'approveProvider'])->name('approve');
        Route::post('/{user}/reject', [UserController::class, 'rejectProvider'])->name('reject');
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{user}/deactivate', [UserController::class, 'deactivateAccount'])->name('deactivate');
        Route::post('/{user}/reactivate', [UserController::class, 'reactivateAccount'])->name('reactivate');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
    });
    // Tags
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::get('/{tag}', [TagController::class, 'show'])->name('show');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
        Route::patch('/{tag}', [TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [TagController::class, 'bulkAction'])->name('bulk-action');
    });
    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{category}/merge', [CategoryController::class, 'merge'])->name('merge');
        Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk-action');
    });
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/analytics', [AdminOrderController::class, 'analytics'])->name('analytics');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update-status', [AdminOrderController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::get('/export', [AdminOrderController::class, 'export'])->name('export');
    });
    // Food Items
    Route::prefix('food-items')->name('food-items.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FoodItemController::class, 'index'])->name('index');
        Route::get('/{foodItem}', [\App\Http\Controllers\Admin\FoodItemController::class, 'show'])->name('show');
    });
    // Notifications, Profile, Settings, Provider Approval
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::get('providers/pending', [\App\Http\Controllers\Admin\ProviderApprovalController::class, 'index'])->name('providers.pending');
    Route::post('providers/{id}/approve', [\App\Http\Controllers\Admin\ProviderApprovalController::class, 'approve'])->name('providers.approve');
    Route::post('providers/{id}/reject', [\App\Http\Controllers\Admin\ProviderApprovalController::class, 'reject'])->name('providers.reject');
});

// ------------------- PROVIDER ROUTES -------------------
Route::middleware(['auth', 'provider', 'active.membership'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('food-items', FoodItemController::class);
    Route::post('food-items/{foodItem}/reactivate', [FoodItemController::class, 'reactivate'])->name('food-items.reactivate');
    Route::post('food-items/{foodItem}/extend-expiry', [FoodItemController::class, 'extendExpiry'])->name('food-items.extendExpiry');
    Route::post('food-items/{foodItem}/mark-sold-out', [FoodItemController::class, 'markSoldOut'])->name('food-items.markSoldOut');
    Route::get('/onboarding', function () {
        return view('provider.onboarding');
    })->name('onboarding');
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Provider\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Provider\OrderController::class, 'show'])->name('show');
        Route::post('/{order}/status', [\App\Http\Controllers\Provider\OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{order}/pickup-time', [\App\Http\Controllers\Provider\OrderController::class, 'setPickupTime'])->name('setPickupTime');
        Route::post('/{order}/note', [\App\Http\Controllers\Provider\OrderController::class, 'addNote'])->name('addNote');
    });
});

// ------------------- CUSTOMER ROUTES -------------------
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Add customer-specific order routes if needed
    // Route::resource('orders', ...)
});

// ------------------- COMMON/AUTH ROUTES -------------------
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/{id}/read', function(Request $request, $id) {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.markAsRead');
    // Membership
    Route::get('/membership/expired', function () {
        return view('membership.expired');
    })->name('membership.expired');
});

// ------------------- PUBLIC/SEARCH/ORDER ROUTES -------------------
// Public search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/{foodItem}', [SearchController::class, 'show'])->name('search.show');
Route::get('/search/providers', [SearchController::class, 'providers'])->name('search.providers');
Route::get('/search/providers/{provider}', [SearchController::class, 'provider'])->name('search.provider');
// Food item detail (protected)
Route::get('/food-items/{foodItem}', [FoodItemController::class, 'show'])->middleware('auth')->name('food-items.show');
// Orders (common for both roles)
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/preparing', [OrderController::class, 'preparing'])->name('orders.preparing');
    Route::post('/orders/{order}/ready', [OrderController::class, 'ready'])->name('orders.ready');
    Route::post('/orders/{order}/collected', [OrderController::class, 'collected'])->name('orders.collected');
    Route::post('/orders/{order}/completed', [OrderController::class, 'completed'])->name('orders.completed');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Provider registration
Route::get('/register/provider', [ProviderRegisterController::class, 'show'])->name('register.provider');
Route::post('/register/provider', [ProviderRegisterController::class, 'register']);

Route::get('/logout-dev', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});


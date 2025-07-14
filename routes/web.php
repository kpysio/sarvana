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

Route::get('/', function () {
    return view('welcome');
});

// Main dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Provider routes
Route::middleware(['auth', 'provider', 'active.membership'])->group(function () {
    Route::get('/provider/dashboard', [DashboardController::class, 'index'])->name('provider.dashboard');
    Route::resource('food-items', FoodItemController::class, ['except' => ['show']]);
    Route::post('food-items/{foodItem}/reactivate', [FoodItemController::class, 'reactivate'])->name('food-items.reactivate');
    Route::post('food-items/{foodItem}/extend-expiry', [FoodItemController::class, 'extendExpiry'])->name('food-items.extendExpiry');
    Route::post('food-items/{foodItem}/mark-sold-out', [FoodItemController::class, 'markSoldOut'])->name('food-items.markSoldOut');
    Route::get('/provider/onboarding', function () {
        return view('provider.onboarding');
    })->name('provider.onboarding');
});

// Customer routes
// Remove the auth middleware from /search routes to make them public
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
});

// Public search routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/{foodItem}', [SearchController::class, 'show'])->name('search.show');
Route::get('/search/providers', [SearchController::class, 'providers'])->name('search.providers');
Route::get('/search/providers/{provider}', [SearchController::class, 'provider'])->name('search.provider');

// Protect food item detail route
Route::get('/food-items/{foodItem}', [FoodItemController::class, 'show'])->middleware('auth')->name('food-items.show');

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
    Route::post('/notifications/{id}/read', function(Request $request, $id) {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.markAsRead');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Admin routes
Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Admin reports
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/sales', [ReportsController::class, 'sales'])->name('sales');
        Route::get('/users', [ReportsController::class, 'users'])->name('users');
        Route::get('/providers', [ReportsController::class, 'providers'])->name('providers');
        Route::get('/orders', [ReportsController::class, 'orders'])->name('orders');
    });
    
    // Admin user management
    Route::prefix('admin/users')->name('admin.users.')->group(function () {
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
    
    // Admin tag management
    Route::prefix('admin/tags')->name('admin.tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::get('/{tag}', [TagController::class, 'show'])->name('show');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
        Route::patch('/{tag}', [TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [TagController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // Admin category management
    Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{category}/merge', [CategoryController::class, 'merge'])->name('merge');
        Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // Admin order management
    Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/analytics', [AdminOrderController::class, 'analytics'])->name('analytics');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update-status', [AdminOrderController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::get('/export', [AdminOrderController::class, 'export'])->name('export');
    });
});

// Admin food item management
Route::prefix('admin/food-items')->middleware(['auth', 'admin'])->name('admin.food-items.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\FoodItemController::class, 'index'])->name('index');
    Route::get('/{foodItem}', [\App\Http\Controllers\Admin\FoodItemController::class, 'show'])->name('show');
    // Add more routes as needed (edit, update, etc.)
});

// Admin Notifications
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('admin.notifications.show');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('admin.notifications.markAllRead');
    // Profile/Settings
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::get('providers/pending', [\App\Http\Controllers\Admin\ProviderApprovalController::class, 'index'])->name('admin.providers.pending');
    Route::post('providers/{id}/approve', [\App\Http\Controllers\Admin\ProviderApprovalController::class, 'approve'])->name('admin.providers.approve');
    Route::post('providers/{id}/reject', [\App\Http\Controllers\Admin\ProviderApprovalController::class, 'reject'])->name('admin.providers.reject');
});

Route::get('/register/provider', [ProviderRegisterController::class, 'show'])->name('register.provider');
Route::post('/register/provider', [ProviderRegisterController::class, 'register']);

require __DIR__.'/auth.php';

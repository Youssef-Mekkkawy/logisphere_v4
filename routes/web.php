<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\logistics\logisticsController;
use App\Http\Controllers\Management\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Main Web Routes - Reorganized Structure
|--------------------------------------------------------------------------
|
| This file loads all modular route files with appropriate middleware
| and organization based on your existing application structure.
|
*/

// ============================================================================
// 🌐 GUEST & PUBLIC ROUTES
// ============================================================================
Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Public shipment tracking (no auth required)
Route::get('/track/{shipmentId}', [ShipmentController::class, 'track'])
    ->name('shipments.track');

// ============================================================================
// 🔐 AUTHENTICATION ROUTES
// ============================================================================
// require __DIR__ . '/auth.php';

// ============================================================================
// ⚡ DEVELOPMENT ROUTES (Remove in production)
// ============================================================================
if (app()->environment('local')) {
    require __DIR__ . '/tools/maintenance.php';
}

// ============================================================================
// 🏠 AUTHENTICATED USER ROUTES
// ============================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard - Main landing page after login
    // require __DIR__ . '/dashboard.php';
    // ===== DASHBOARD & LOGOUT =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Standard logout route (with CSRF protection)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Alternative GET logout route (for when CSRF fails)
    Route::get('/force-logout', [LoginController::class, 'forceLogout'])->name('force.logout');

    // CSRF token refresh endpoint
    Route::get('/refresh-token', [LoginController::class, 'refreshToken'])->name('refresh.token');


    // ========================================================================
    // 👤 SHARED USER FEATURES
    // ========================================================================
    Route::prefix('user')->name('user.')->group(function () {
        // require __DIR__ . '/shared/profile.php';
        require __DIR__ . '/shared/notifications.php';
    });

    // Settings (available to all authenticated users)
    require __DIR__ . '/shared/settings.php';
});

// ============================================================================
// 🔐 PERMISSION-BASED ROUTES
// ============================================================================
Route::middleware(['auth'])->group(function () {

    // ========================================================================
    // 📋 MANAGEMENT ROUTES - Core Business Modules
    // ========================================================================
    Route::prefix('management')->name('management.')->group(function () {
        require __DIR__ . '/management/accounting.php';
        require __DIR__ . '/management/companies.php';
        require __DIR__ . '/management/employees.php';
        require __DIR__ . '/management/shipments.php';
    });

    // ========================================================================
    // 🚢 LOGISTICS ROUTES
    // ========================================================================
    Route::prefix('logistics')->name('logistics.')->group(function () {
        Route::get('/', [logisticsController::class, 'index'])->name('index');
        require __DIR__ . '/logistics/agencies.php';
        require __DIR__ . '/logistics/bosla_gomrok.php';
        require __DIR__ . '/logistics/consignee_notify.php';
        require __DIR__ . '/logistics/container_loading.php';
        require __DIR__ . '/logistics/coo_types.php';
        require __DIR__ . '/logistics/destinations.php';
        require __DIR__ . '/logistics/inspection_types.php';
        require __DIR__ . '/logistics/ports.php';
        require __DIR__ . '/logistics/quantity_types.php';
        require __DIR__ . '/logistics/shipping_agencies.php';
        require __DIR__ . '/logistics/shipment_types.php';
        require __DIR__ . '/logistics/services.php';
        require __DIR__ . '/logistics/shippers.php';
    });

    // ========================================================================
    // 💰 ACCOUNTING ROUTES - Financial Management
    // ========================================================================
    Route::middleware(['permission:accounting.view'])->group(function () {
        Route::prefix('accounting')->name('accounting.')->group(function () {
            require __DIR__ . '/management/accounting.php';
        });
    });



    // ========================================================================
    // 🔧 TOOLS ROUTES - File Management & Utilities
    // ========================================================================
    Route::prefix('tools')->name('tools.')->group(function () {
        require __DIR__ . '/tools/file.php';
        require __DIR__ . '/tools/utilities.php';
    });

    // ========================================================================
    // 🔐 ADMIN ROUTES - Role & Permission Management
    // ========================================================================
    Route::prefix('auth')->name('auth.')->group(function () {
        require __DIR__ . '/auth/roles.php'; // Role & Permissions
        // require __DIR__ . '/auth/permissions.php';
        require __DIR__ . '/auth/users.php';
    });

    // ========================================================================
    // ⚡ QUICK ACCESS & API ROUTES
    // ========================================================================
    require __DIR__ . '/shared/errors.php';


    // ===== API ENDPOINTS =====
    Route::prefix('api')->name('api.')->group(function () {
        // Dashboard
        Route::get('/dashboard-metrics', [DashboardController::class, 'getMetrics'])->name('dashboard.metrics');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', function () {
            return view('shared.profile.show');
        })->name('profile.show');
    });
});

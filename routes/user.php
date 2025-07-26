<?php

// File: routes/web.php (Updated Routes)
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\UserController;
// use App\Http\Controllers\auth\UpdatedUserController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\PermissionController;

sdffsdfsdfsd

Route::middleware(['auth'])->group(function () {

    // ===== INTEGRATED USER MANAGEMENT ROUTES =====
    Route::prefix('users')->group(function () {
        // Main user management routes
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Integrated role management within users section
        Route::post('/roles', [UserController::class, 'storeRole'])->name('users.roles.store');
        Route::delete('/roles/{role}', [UserController::class, 'destroyRole'])->name('users.roles.destroy');
        Route::get('/roles/{role}', [UserController::class, 'getRole'])->name('users.roles.get');

        // Integrated permission management within users section
        Route::post('/permissions', [UserController::class, 'storePermission'])->name('users.permissions.store');
        Route::delete('/permissions/{permission}', [UserController::class, 'destroyPermission'])->name('users.permissions.destroy');
        Route::get('/permissions/{permission}', [UserController::class, 'getPermission'])->name('users.permissions.get');

        // AJAX endpoints for getting user data
        Route::get('/{user}/data', [UserController::class, 'getUser'])->name('users.get');
    });

    // ===== STANDALONE ROLE MANAGEMENT ROUTES (Optional - for direct access) =====
    Route::middleware(['permission:roles.view'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    });

    // ===== STANDALONE PERMISSION MANAGEMENT ROUTES (Optional - for direct access) =====
    Route::middleware(['permission:roles.manage-permissions'])->group(function () {
        Route::resource('permissions', PermissionController::class);
        Route::patch('permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    });
});

// ===== API ROUTES FOR AJAX CALLS =====
Route::middleware(['auth'])->prefix('api')->group(function () {
    // User API endpoints
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Role API endpoints
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [UserController::class, 'storeRole']);
    Route::get('/roles/{role}', [UserController::class, 'getRole']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [UserController::class, 'destroyRole']);

    // Permission API endpoints
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/permissions', [UserController::class, 'storePermission']);
    Route::get('/permissions/{permission}', [UserController::class, 'getPermission']);
    Route::put('/permissions/{permission}', [PermissionController::class, 'update']);
    Route::delete('/permissions/{permission}', [UserController::class, 'destroyPermission']);
});

Route::middleware(['auth'])->group(function () {

    // Role Management Routes
    Route::resource('roles', RoleController::class);
    Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');

    // Permission Management Routes
    Route::resource('permissions', PermissionController::class);
    Route::patch('permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');

    // Update existing user routes to use the new controller
    // Route::resource('users', UpdatedUserController::class);
});

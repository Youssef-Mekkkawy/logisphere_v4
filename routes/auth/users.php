<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\PasswordChangeController;
// routes/auth/user
Route::prefix('users')->name('users.')->group(function () {
    // Main user CRUD
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

    // 🔥 NEW: User Account Management
    // Enhanced Account Management
    Route::patch('/{user}/toggle-block', [UserController::class, 'toggleBlock'])->name('toggle-block');
    Route::patch('/{user}/force-password-reset', [UserController::class, 'forcePasswordReset'])->name('force-password-reset');

    // 🔥 NEW: Force logout specific user
    Route::post('/{user}/force-logout', [UserController::class, 'forceLogout'])->name('force-logout');

    Route::get('/{user}/status', [UserController::class, 'getUserStatus'])->name('status');

    // 🔥 ROLE MANAGEMENT (INTEGRATED)
    Route::post('/roles', [UserController::class, 'storeRole'])->name('roles.store');
    Route::delete('/roles/{role}', [UserController::class, 'destroyRole'])->name('roles.destroy');
    Route::get('/roles/{role}', [UserController::class, 'getRole'])->name('roles.get');

    // 🔥 PERMISSION MANAGEMENT (INTEGRATED)  
    Route::post('/permissions', [UserController::class, 'storePermission'])->name('permissions.store');
    Route::delete('/permissions/{permission}', [UserController::class, 'destroyPermission'])->name('permissions.destroy');
    Route::get('/permissions/{permission}', [UserController::class, 'getPermission'])->name('permissions.get');

    // 🔥 USER AJAX ENDPOINTS

    Route::get('/get-user/{user}', [UserController::class, 'getUser'])->name('get-user');
});


// Password Change Routes
Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::get('/change-password', [PasswordChangeController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/change-password', [PasswordChangeController::class, 'changePassword'])->name('password.update');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;

Route::prefix('users')->name('users.')->group(function () {
    // Main user CRUD
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

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

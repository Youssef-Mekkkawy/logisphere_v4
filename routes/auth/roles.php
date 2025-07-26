<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RoleController,
    PermissionController
};
// ===== STANDALONE ROUTES (OPTIONAL) =====
// Route::resource('roles', RoleController::class);
// Route::resource('permissions', PermissionController::class);

// ===== STANDALONE ROLE & PERMISSION MANAGEMENT (Optional - Direct Access) =====
Route::middleware('permission:roles.view')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::patch('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
});

Route::middleware('permission:roles.manage-permissions')->group(function () {
    Route::resource('permissions', PermissionController::class);
    Route::patch('/permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
});

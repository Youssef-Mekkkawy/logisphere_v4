<?php

use App\Http\Controllers\Shared\SettingsController;
use Illuminate\Support\Facades\Route;



// ===== SETTINGS =====
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/preferences', [SettingsController::class, 'updatePreferences'])->name('update-preferences');
    Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('update-notifications');

    // Change Password
    Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [SettingsController::class, 'updatePassword'])->name('update-password');

    // System Settings (Admin only)
    Route::middleware('permission:settings.edit-system')->group(function () {
        Route::get('/system', [SettingsController::class, 'systemSettings'])->name('system');
        Route::post('/system', [SettingsController::class, 'updateSystemSettings'])->name('update-system');
    });
});

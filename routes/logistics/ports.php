<?php

use App\Http\Controllers\logistics\PortController;
use Illuminate\Support\Facades\Route;

Route::resource('ports', PortController::class, [
    'names' => [
        'index' => 'ports.index',
        'create' => 'ports.create',
        'store' => 'ports.store',
        'show' => 'ports.show',
        'edit' => 'ports.edit',
        'update' => 'ports.update',
        'destroy' => 'ports.destroy'
    ]
]);

// Master Data
Route::get('/ports/active', [PortController::class, 'getActive'])->name('ports.active');

// ===== MASTER DATA MANAGEMENT =====
// Route::middleware('permission:settings.manage-ports')->group(function () {
//     Route::resource('ports', PortController::class);
//     Route::patch('/ports/{port}/toggle-status', [PortController::class, 'toggleStatus'])->name('ports.toggle-status');
//     Route::get('/ports/{port}/statistics', [PortController::class, 'statistics'])->name('ports.statistics');
// });

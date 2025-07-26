<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Logistics\COOTypeController;

// COO Types Resource Routes
Route::resource('coo-types', COOTypeController::class, [
    'names' => [
        'index' => 'coo-types.index',
        'create' => 'coo-types.create',
        'store' => 'coo-types.store',
        'show' => 'coo-types.show',
        'edit' => 'coo-types.edit',
        'update' => 'coo-types.update',
        'destroy' => 'coo-types.destroy'
    ]
]);
// Additional COO Types API routes (optional)
Route::prefix('coo-types')->name('coo-types.')->group(function () {
    Route::get('/{cooType}/statistics', [COOTypeController::class, 'statistics'])->name('statistics');
    Route::post('/{cooType}/toggle-status', [COOTypeController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/active', [COOTypeController::class, 'getActive'])->name('active');
});

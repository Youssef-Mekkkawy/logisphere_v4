<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistics\QuantityTypeController;
// Quantity Types Resource Routes


Route::resource('quantity-type', QuantityTypeController::class, [
    'names' => [
        'index' => 'quantity-types.index',
        'create' => 'quantity-types.create',
        'store' => 'quantity-types.store',
        'show' => 'quantity-types.show',
        'edit' => 'quantity-types.edit',
        'update' => 'quantity-types.update',
        'destroy' => 'quantity-types.destroy'
    ]
]);

// AJAX routes

Route::get('/export', [QuantityTypeController::class, 'export'])->name('export');


// API routes for AJAX functionality
Route::get('/export', [QuantityTypeController::class, 'export'])->name('logistics.quantity-types.export');
Route::post('/convert', [QuantityTypeController::class, 'convertQuantity'])->name('logistics.quantity-types.convert');
Route::patch('/logistics.quantity-types/{quantityType}/toggle-status', [QuantityTypeController::class, 'toggleStatus'])
    ->name('logistics.quantity-types.toggle-status');
// Quantity Types Extended AJAX Actions
Route::prefix('quantity-types')->name('quantity-types.')->group(function () {
    Route::patch('/{quantityType}/toggle-status', [QuantityTypeController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/by-criteria', [QuantityTypeController::class, 'getByCriteria'])->name('by-criteria');
    Route::get('/by-category', [QuantityTypeController::class, 'getByCategory'])->name('by-category');
    Route::get('/standard', [QuantityTypeController::class, 'getStandard'])->name('standard');
    Route::post('/validate-quantity', [QuantityTypeController::class, 'validateQuantity'])->name('validate-quantity');
    Route::post('/convert-quantity', [QuantityTypeController::class, 'convertQuantity'])->name('convert-quantity');
    Route::get('/{quantityType}/statistics', [QuantityTypeController::class, 'getStatistics'])->name('statistics');
    Route::post('/update-sort-order', [QuantityTypeController::class, 'updateSortOrder'])->name('update-sort-order');
    Route::get('/compatibility', [QuantityTypeController::class, 'getCompatibility'])->name('compatibility');
});


// ===== API ENDPOINTS =====
Route::prefix('api')->name('api.')->group(function () {
    // ... existing API routes

    // Quantity Types API
    Route::get('/quantity-types/active', [QuantityTypeController::class, 'getByCriteria'])->name('quantity-types.active');
    Route::get('/quantity-types/by-category/{category}', [QuantityTypeController::class, 'getByCategory'])->name('quantity-types.by-category');
    Route::get('/quantity-types/standard', [QuantityTypeController::class, 'getStandard'])->name('quantity-types.standard');
    Route::post('/quantity-types/validate', [QuantityTypeController::class, 'validateQuantity'])->name('quantity-types.validate');
    Route::post('/quantity-types/convert', [QuantityTypeController::class, 'convertQuantity'])->name('quantity-types.convert');
    Route::get('/quantity-types/compatibility', [QuantityTypeController::class, 'getCompatibility'])->name('quantity-types.compatibility');
});

<?php

use App\Http\Controllers\Management\ShipmentController;
use Illuminate\Support\Facades\Route;
// ===== CORE RESOURCE MANAGEMENT =====
// Route::resource('shipments', ShipmentController::class);

Route::resource('shipments', ShipmentController::class, [
    'names' => [
        'index' => 'shipments.index',
        'create' => 'shipments.create',
        'store' => 'shipments.store',
        'show' => 'shipments.show',
        'edit' => 'shipments.edit',
        'update' => 'shipments.update',
        'destroy' => 'shipments.destroy'
    ]
]);


// ===== SHIPMENT EXTENDED ACTIONS =====
Route::prefix('shipments')->name('shipments.')->group(function () {
    Route::patch('/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('update-status');
    Route::patch('/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('update-status');
    Route::get('/{shipment}/document/{type}', [ShipmentController::class, 'generateDocument'])->name('document');
    Route::post('/{shipment}/duplicate', [ShipmentController::class, 'duplicate'])->name('duplicate');
    Route::patch('/{shipment}/toggle-archive', [ShipmentController::class, 'toggleArchive'])->name('toggle-archive');
});
// ===== API ENDPOINTS =====
Route::prefix('api')->name('api.')->group(function () {
    // Shipments
    Route::get('/shipments/search', [ShipmentController::class, 'search'])->name('shipments.search');
    Route::get('/shipments/{shipment}/tracking', [ShipmentController::class, 'getTracking'])->name('shipments.tracking');
});

<?php

use App\Http\Controllers\logistics\ShipmentTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('shipment-types', ShipmentTypeController::class, [
    'names' => [
        'index' => 'shipment-types.index',
        'create' => 'shipment-types.create',
        'store' => 'shipment-types.store',
        'show' => 'shipment-types.show',
        'edit' => 'shipment-types.edit',
        'update' => 'shipment-types.update',
        'destroy' => 'shipment-types.destroy'
    ]
]);


Route::get('/shipment-types', [ShipmentTypeController::class, 'index'])->name('shipment-types.index');
Route::get('/shipment-types/active', [ShipmentTypeController::class, 'getActive'])->name('shipment-types.active');
Route::get('/shipment-types/category/{category}', [ShipmentTypeController::class, 'getByCategory'])->name('shipment-types.by-category');

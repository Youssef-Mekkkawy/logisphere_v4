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
Route::patch('/{quantityType}/toggle-status', [QuantityTypeController::class, 'toggleStatus'])->name('logistics.quantity-types.toggle-status');
Route::get('/export', [QuantityTypeController::class, 'export'])->name('logistics.quantity-types.export');
Route::post('/convert', [QuantityTypeController::class, 'convertQuantity'])->name('logistics.quantity-types.convert');
Route::patch('/logistics.quantity-types/{quantityType}/toggle-status', [QuantityTypeController::class, 'toggleStatus'])
    ->name('logistics.quantity-types.toggle-status');

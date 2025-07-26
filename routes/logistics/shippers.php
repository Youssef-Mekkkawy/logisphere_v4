<?php

use App\Http\Controllers\logistics\ShipperController;
use Illuminate\Support\Facades\Route;

Route::resource('shippers', ShipperController::class, [
    'names' => [
        'index' => 'shippers.index',
        'create' => 'shippers.create',
        'store' => 'shippers.store',
        'show' => 'shippers.show',
        'edit' => 'shippers.edit',
        'update' => 'shippers.update',
        'destroy' => 'shippers.destroy'
    ]
]);

// Master Data
Route::get('/shippers/active', [ShipperController::class, 'getActive'])->name('shippers.active');

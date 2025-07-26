<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Logistics\ShippingAgencyController;

Route::resource('shipping-agencies', ShippingAgencyController::class, [
    'names' => [
        'index' => 'shipping-agencies.index',
        'create' => 'shipping-agencies.create',
        'store' => 'shipping-agencies.store',
        'show' => 'shipping-agencies.show',
        'edit' => 'shipping-agencies.edit',
        'update' => 'shipping-agencies.update',
        'destroy' => 'shipping-agencies.destroy'
    ]
]);

Route::get('/shipping-agencies/active', [ShippingAgencyController::class, 'getActive'])->name('shipping-agencies.active');
Route::get('/shipping-agencies/country/{country}', [ShippingAgencyController::class, 'getByCountry'])->name('shipping-agencies.by-country');
Route::get('/shipping-agencies/search', [ShippingAgencyController::class, 'search'])->name('shipping-agencies.search');

<?php

use App\Http\Controllers\logistics\DestinationController;
use Illuminate\Support\Facades\Route;


Route::resource('destinations', DestinationController::class, [
    'names' => [
        'index' => 'destinations.index',
        'create' => 'destinations.create',
        'store' => 'destinations.store',
        'show' => 'destinations.show',
        'edit' => 'destinations.edit',
        'update' => 'destinations.update',
        'destroy' => 'destinations.destroy'
    ]
]);

Route::get('destinations/api/by-criteria', [DestinationController::class, 'getByCriteria'])->name('destinations.by-criteria');
Route::get('destinations/api/nearby', [DestinationController::class, 'getNearby'])->name('destinations.nearby');
Route::patch('destinations/{destination}/toggle-status', [DestinationController::class, 'toggleStatus'])->name('destinations.toggle-status');
Route::get('destinations/{destination}/availability', [DestinationController::class, 'checkAvailability'])->name('destinations.availability');

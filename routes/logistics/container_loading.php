<?php

use App\Http\Controllers\logistics\ContainerLoadingController;
use Illuminate\Support\Facades\Route;

Route::resource('container-loading', ContainerLoadingController::class, [
    'names' => [
        'index' => 'container-loading.index',
        'create' => 'container-loading.create',
        'store' => 'container-loading.store',
        'show' => 'container-loading.show',
        'edit' => 'container-loading.edit',
        'update' => 'container-loading.update',
        'destroy' => 'container-loading.destroy'
    ]
]);
// Additional routes for Container Loading
Route::get('container-loading/api/by-criteria', [ContainerLoadingController::class, 'getByCriteria'])->name('container-loading.by-criteria');
Route::patch('container-loading/{containerLoading}/toggle-status', [ContainerLoadingController::class, 'toggleStatus'])->name('container-loading.toggle-status');
Route::get('container-loading/{containerLoading}/availability', [ContainerLoadingController::class, 'checkAvailability'])->name('container-loading.availability');

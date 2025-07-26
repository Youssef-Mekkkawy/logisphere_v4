<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistics\ServiceController;

// Services Resource Routes
Route::resource('services', ServiceController::class, [
    'names' => [
        'index' => 'services.index',
        'create' => 'services.create',
        'store' => 'services.store',
        'show' => 'services.show',
        'edit' => 'services.edit',
        'update' => 'services.update',
        'destroy' => 'services.destroy'
    ]
]);

// Services Additional Routes
Route::prefix('services')->name('services.')->group(function () {
    Route::patch('/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/{service}/statistics', [ServiceController::class, 'getStatistics'])->name('statistics');
    Route::get('/by-category/{category}', [ServiceController::class, 'getByCategory'])->name('by-category');
    Route::get('/mandatory', [ServiceController::class, 'getMandatoryServices'])->name('mandatory');
    Route::post('/{service}/calculate-rate', [ServiceController::class, 'calculateRate'])->name('calculate-rate');
    Route::get('/{service}/usage-report', [ServiceController::class, 'getUsageReport'])->name('usage-report');
    Route::get('/for-cargo-type', [ServiceController::class, 'getForCargoType'])->name('for-cargo-type');
    Route::get('/{service}/availability', [ServiceController::class, 'checkAvailability'])->name('availability');
    Route::get('/by-documents', [ServiceController::class, 'getByRequiredDocuments'])->name('by-documents');
    Route::get('/{service}/performance-report', [ServiceController::class, 'generatePerformanceReport'])->name('performance-report');
});

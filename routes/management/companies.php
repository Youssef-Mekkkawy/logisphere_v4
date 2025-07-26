<?php

use App\Http\Controllers\Management\CompanyController;
use Illuminate\Support\Facades\Route;
// ===== CORE RESOURCE MANAGEMENT =====
Route::resource('companies', CompanyController::class);


// ===== COMPANY EXTENDED ACTIONS =====
Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/{company}/shipments', [CompanyController::class, 'shipments'])->name('shipments');
    Route::get('/{company}/performance', [CompanyController::class, 'performance'])->name('performance');
    Route::patch('/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('toggle-status');
});
// ===== API ENDPOINTS =====
Route::prefix('api')->name('api.')->group(function () {
    // Companies
    Route::get('/companies/search', [CompanyController::class, 'search'])->name('companies.search');
    Route::get('/companies/{company}/metrics', [CompanyController::class, 'getMetrics'])->name('companies.metrics');
});

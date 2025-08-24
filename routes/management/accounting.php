<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\Accounting\AccountingDashboardController;

Route::resource('account', AccountingDashboardController::class);
// ===== ACCOUNTING SYSTEM =====
Route::middleware('permission:accounting.view')->prefix('account')->name('account.')->group(function () {
    
    Route::get('/', [AccountingDashboardController::class, 'index'])->name('index');
    // Financial Reports
    Route::middleware('permission:accounting.generate-reports')->group(function () {
        Route::get('/reports', [AccountingDashboardController::class, 'reports'])->name('reports');
        Route::get('/reports/employee-summary', [AccountingDashboardController::class, 'employeeSummary'])->name('reports.employee-summary');
        Route::get('/reports/company-summary', [AccountingDashboardController::class, 'companySummary'])->name('reports.company-summary');
    });
});

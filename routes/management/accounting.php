<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\Accounting\{
    AccountController,
    AccountingDashboardController,
    AdvanceController,
    ExpenseController,
    InvoiceController,
    JobController,
    PaymentController,
    ReportController
};

// ===== ACCOUNTING SYSTEM =====
Route::middleware('permission:accounting.view')->prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/', [AccountingDashboardController::class, 'index'])->name('index');

    // Employee Jobs & Advances
    Route::get('/employee-jobs', [JobController::class, 'employeeJobs'])->name('employee-jobs');
    Route::get('/employee-covenant', [AccountingDashboardController::class, 'employeeCovenant'])->name('employee-covenant');
    Route::get('/advance-types', [AccountingDashboardController::class, 'advanceTypes'])->name('advance-types');

    // Company Payments
    Route::get('/company-payments', [AccountingDashboardController::class, 'companyPayments'])->name('company-payments');

    // Financial Reports
    Route::middleware('permission:accounting.generate-reports')->group(function () {
        Route::get('/reports', [AccountingDashboardController::class, 'reports'])->name('reports');
        Route::get('/reports/employee-summary', [AccountingDashboardController::class, 'employeeSummary'])->name('reports.employee-summary');
        Route::get('/reports/company-summary', [AccountingDashboardController::class, 'companySummary'])->name('reports.company-summary');
    });
});

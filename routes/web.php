<?php

// ================================================================================================
// WELL-STRUCTURED ACCOUNTING ROUTES - ORGANIZED & CLEAN
// ================================================================================================

// File: routes/web.php (Replace your accounting routes with this organized version)
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\SubmenuController;
use App\Http\Controllers\SettingsController;
use App\Models\Account;
use App\Models\Employee;
use App\Http\Controllers\Accounting\{
    AccountingDashboardController,
    InvoiceController,
    PaymentController,
    ExpenseController,
    AdvanceController,
    JobController,
    ReportController,
    AccountController,
    SettingsController as AccountingSettingsController
};

// Guest routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // File management
    Route::get('/file', [FileController::class, 'index'])->name('file.index');
    Route::post('/file/export', [FileController::class, 'export'])->name('file.export');
    Route::post('/file/import', [FileController::class, 'import'])->name('file.import');

    // Submenu management
    Route::prefix('submenu')->name('submenu.')->group(function () {
        Route::get('/', [SubmenuController::class, 'index'])->name('index');
        Route::get('/ports', [SubmenuController::class, 'ports'])->name('ports');
        Route::get('/agencies', [SubmenuController::class, 'agencies'])->name('agencies');
        Route::get('/types', [SubmenuController::class, 'types'])->name('types');
        Route::post('/ports', [SubmenuController::class, 'storePorts'])->name('ports.store');
        Route::post('/agencies', [SubmenuController::class, 'storeAgencies'])->name('agencies.store');
        Route::post('/types', [SubmenuController::class, 'storeTypes'])->name('types.store');
    });

    // Companies
    Route::resource('companies', CompanyController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Users (Admin only using Gates)
    Route::middleware(['can:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Shipments
    Route::resource('shipments', ShipmentController::class);
    Route::get('/shipments/{shipmentId}/track', [ShipmentController::class, 'track'])->name('shipments.track');
    // Accounting
    // ================================================================================================
    // ACCOUNTING ROUTES - CLEAN & ORGANIZED
    // ================================================================================================

    // Route::prefix('accounting')->name('accounting.')->middleware(['auth'])->group(function () {

    //     // Dashboard
    //     Route::get('/', [AccountingDashboardController::class, 'index'])->name('index');

    //     // Invoices - Full Resource with Custom Routes
    //     Route::resource('invoices', InvoiceController::class)->except(['create', 'store']);
    //     Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    //     Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    //     Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');
    //     Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'sendToClient'])->name('invoices.send');

    //     // Payments - Resource with Custom Routes
    //     Route::resource('payments', PaymentController::class)->only(['index', 'show', 'destroy']);
    //     Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    //     Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    //     // Expenses - Full Resource with Approval Routes
    //     Route::resource('expenses', ExpenseController::class);
    //     Route::put('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve')->middleware('can:approve-expenses');
    //     Route::put('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject')->middleware('can:approve-expenses');

    //     // Employee Advances - Resource with Repayment Routes
    //     Route::resource('advances', AdvanceController::class)->except(['edit', 'update']);
    //     Route::get('/advances/{advance}/repay', [AdvanceController::class, 'showRepaymentForm'])->name('advances.repay');
    //     Route::post('/advances/{advance}/repay', [AdvanceController::class, 'recordRepayment'])->name('advances.repay.store');
    //     Route::put('/advances/{advance}/writeoff', [AdvanceController::class, 'writeOff'])->name('advances.writeoff')->middleware('can:write-off-advances');

    //     // Job Assignments - Full Resource
    //     Route::resource('jobs', JobController::class);
    //     Route::put('/jobs/{job}/complete', [JobController::class, 'markComplete'])->name('jobs.complete');
    //     Route::put('/jobs/{job}/bill', [JobController::class, 'markBillable'])->name('jobs.bill');

    //     // Financial Reports
    //     Route::prefix('reports')->name('reports.')->middleware('can:view-financial-reports')->group(function () {
    //         Route::get('/', [ReportController::class, 'index'])->name('index');
    //         Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
    //         Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet');
    //         Route::get('/cash-flow', [ReportController::class, 'cashFlow'])->name('cash-flow');
    //         Route::get('/aging', [ReportController::class, 'aging'])->name('aging');
    //         Route::get('/export/{type}', [ReportController::class, 'export'])->name('export')->middleware('can:export-data');
    //     });

    //     // Chart of Accounts - Admin Only
    //     Route::resource('accounts', AccountController::class)->middleware('can:manage-accounts');

    //     // Settings - Admin Only
    //     Route::prefix('settings')->name('settings.')->middleware('can:manage-accounting-settings')->group(function () {
    //         Route::get('/', [AccountingSettingsController::class, 'index'])->name('index');
    //         Route::post('/update', [AccountingSettingsController::class, 'update'])->name('update');
    //         Route::post('/backup', [AccountingSettingsController::class, 'createBackup'])->name('backup');
    //     });
    // });


    // Accounting Module
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');

        // Employee Jobs
        Route::resource('jobs', JobController::class);

        // Employee Covenants (Equipment)
        // Route::get('employee-covenants');

        // // Advance Types
        // Route::get('advance-types');

        // // Employee Advances
        // Route::get('employee-advances');
        // // Employee Covenants (Equipment)
        // Route::resource('employee-covenants', EmployeeCovenantController::class);

        // // Advance Types
        // Route::resource('advance-types', AdvanceTypeController::class);

        // // Employee Advances
        // Route::resource('employee-advances', EmployeeAdvanceController::class);

        // Invoices & Payments
        Route::resource('invoices', InvoiceController::class);
        Route::resource('payments', PaymentController::class);
    });


    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
});

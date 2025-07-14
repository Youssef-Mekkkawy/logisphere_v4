<?php

use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountingDashboardController;
use App\Http\Controllers\Accounting\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\FileController;
use App\Http\Controllers\SubmenuController;
use App\Http\Controllers\SettingsController;

// ===== GUEST ROUTES =====
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== PUBLIC TRACKING =====
Route::get('/track/{shipmentId}', [ShipmentController::class, 'track'])->name('shipments.track');

// ===== AUTHENTICATED ROUTES =====
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== SHIPMENT MANAGEMENT =====
    Route::resource('shipments', ShipmentController::class);
    Route::patch('/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    Route::get('/shipments/{shipment}/document/{type}', [ShipmentController::class, 'generateDocument'])->name('shipments.document');

    // ===== COMPANY MANAGEMENT =====
    Route::resource('companies', CompanyController::class);
    Route::get('/companies/{company}/shipments', [CompanyController::class, 'shipments'])->name('companies.shipments');
    Route::get('/companies/{company}/performance', [CompanyController::class, 'performance'])->name('companies.performance');

    // ===== EMPLOYEE MANAGEMENT =====
    Route::resource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/performance', [EmployeeController::class, 'performance'])->name('employees.performance');
    Route::get('/employees/{employee}/shipments', [EmployeeController::class, 'shipments'])->name('employees.shipments');

    // ===== USER MANAGEMENT (Admin only) =====
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // ===== FILE MANAGEMENT =====
    Route::prefix('file')->name('file.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::post('/export', [FileController::class, 'export'])->name('export');
        Route::post('/import', [FileController::class, 'import'])->name('import');
    });

    // ===== SUBMENU MANAGEMENT =====
    Route::prefix('submenu')->name('submenu.')->group(function () {
        Route::get('/', [SubmenuController::class, 'index'])->name('index');

        // Shipment Information
        Route::get('/ports', [SubmenuController::class, 'ports'])->name('ports');
        Route::get('/shipping-agency', [SubmenuController::class, 'shippingAgency'])->name('shipping-agency');
        Route::get('/shipment-types', [SubmenuController::class, 'shipmentTypes'])->name('shipment-types');

        // Customs Clearance
        Route::get('/coo-types', [SubmenuController::class, 'cooTypes'])->name('coo-types');
        Route::get('/inspection-types', [SubmenuController::class, 'inspectionTypes'])->name('inspection-types');

        // Trucking
        Route::get('/bosla-gomrok', [SubmenuController::class, 'boslaGomrok'])->name('bosla-gomrok');
        Route::get('/destinations', [SubmenuController::class, 'destinations'])->name('destinations');
        Route::get('/load-containers', [SubmenuController::class, 'loadContainers'])->name('load-containers');
        Route::get('/consignee-notify', [SubmenuController::class, 'consigneeNotify'])->name('consignee-notify');

        // Ocean Freight
        Route::get('/quantity-types', [SubmenuController::class, 'quantityTypes'])->name('quantity-types');
        Route::get('/shippers', [SubmenuController::class, 'shippers'])->name('shippers');
    });

    // ===== ACCOUNTING SYSTEM =====
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/', [AccountingDashboardController::class, 'index'])->name('index');

        // Employee Jobs & Advances
        Route::get('/employee-jobs', [JobController::class, 'employeeJobs'])->name('employee-jobs');
        Route::get('/employee-covenant', [AccountingDashboardController::class, 'employeeCovenant'])->name('employee-covenant');
        Route::get('/advance-types', [AccountingDashboardController::class, 'advanceTypes'])->name('advance-types');

        // Company Payments
        Route::get('/company-payments', [AccountingDashboardController::class, 'companyPayments'])->name('company-payments');

        // Financial Reports
        Route::get('/reports', [AccountingDashboardController::class, 'reports'])->name('reports');
        Route::get('/reports/employee-summary', [AccountingDashboardController::class, 'employeeSummary'])->name('reports.employee-summary');
        Route::get('/reports/company-summary', [AccountingDashboardController::class, 'companySummary'])->name('reports.company-summary');
    });

    // ===== SETTINGS =====
    // Route::prefix('settings')->name('settings.')->group(function () {
    //     Route::get('/', [SettingsController::class, 'index'])->name('index');
    //     Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');
    //     Route::post('/update-password', [SettingsController::class, 'updatePassword'])->name('update-password');
    //     Route::get('/user-settings', [SettingsController::class, 'userSettings'])->name('user-settings');
    //     Route::post('/update-user-settings', [SettingsController::class, 'updateUserSettings'])->name('update-user-settings');
    // });
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/preferences', [SettingsController::class, 'updatePreferences'])->name('update-preferences');
        Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('update-notifications');

        // Change Password
        Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');
        Route::post('/change-password', [SettingsController::class, 'updatePassword'])->name('update-password');

        // System Settings (Admin only)
        Route::middleware('can:manage-settings')->group(function () {
            Route::get('/system', [SettingsController::class, 'systemSettings'])->name('system');
            Route::post('/system', [SettingsController::class, 'updateSystemSettings'])->name('update-system');
        });
    });

    // ===== API ROUTES FOR AJAX CALLS =====
    Route::prefix('api')->name('api.')->group(function () {
        // Dashboard Data
        Route::get('/dashboard-metrics', [DashboardController::class, 'getMetrics'])->name('dashboard.metrics');

        // Shipment API
        Route::get('/shipments/search', [ShipmentController::class, 'search'])->name('shipments.search');
        Route::get('/shipments/{shipment}/tracking', [ShipmentController::class, 'getTracking'])->name('shipments.tracking');

        // Company API
        Route::get('/companies/search', [CompanyController::class, 'search'])->name('companies.search');
        Route::get('/companies/{company}/metrics', [CompanyController::class, 'getMetrics'])->name('companies.metrics');

        // Employee API
        Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
        Route::get('/employees/{employee}/metrics', [EmployeeController::class, 'getMetrics'])->name('employees.metrics');
    });
});

// ===== KEYBOARD SHORTCUTS MAPPING =====
// These routes handle the keyboard shortcuts mentioned in your system:
Route::middleware(['auth'])->group(function () {
    // Ctrl+F1: Create new shipment
    Route::get('/quick/shipment/create', [ShipmentController::class, 'create'])->name('quick.shipment.create');

    // Ctrl+U: Users section
    Route::get('/quick/users', [UserController::class, 'index'])->name('quick.users');

    // Ctrl+E: Employee section
    Route::get('/quick/employees', [EmployeeController::class, 'index'])->name('quick.employees');

    // Ctrl+G: Change password
    Route::get('/quick/change-password', [SettingsController::class, 'changePassword'])->name('quick.change-password');

    // Alt+S: Service management
    Route::get('/quick/services', [SubmenuController::class, 'services'])->name('quick.services');
});

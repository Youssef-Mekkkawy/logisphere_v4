<?php

use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountingDashboardController;
use App\Http\Controllers\Accounting\JobController;
use App\Http\Controllers\PortController;
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
use App\Http\Controllers\ShipmentTypeController;
use App\Http\Controllers\ShippingAgencyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UpdatedUserController;
// ======================================================================================
// GUEST ROUTES
// ======================================================================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']); // ✅ FIXED: Removed confusing name 'loginForm'


// ======================================================================================
// PUBLIC TRACKING
// ======================================================================================
Route::get('/track/{shipmentId}', [ShipmentController::class, 'track'])->name('shipments.track');

// ======================================================================================
// AUTHENTICATED ROUTES
// ======================================================================================
Route::middleware(['auth'])->group(function () {

    // ===== DASHBOARD =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // ======================================================================================
    // MAIN RESOURCE ROUTES
    // ======================================================================================

    // ===== SHIPMENT MANAGEMENT =====
    Route::resource('shipments', ShipmentController::class);
    Route::patch('/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    Route::get('/shipments/{shipment}/document/{type}', [ShipmentController::class, 'generateDocument'])->name('shipments.document');
    Route::post('shipments/{shipment}/duplicate', [ShipmentController::class, 'duplicate'])->name('shipments.duplicate');
    Route::patch('shipments/{shipment}/toggle-archive', [ShipmentController::class, 'toggleArchive'])->name('shipments.toggle-archive');

    // ===== COMPANY MANAGEMENT =====
    Route::resource('companies', CompanyController::class);
    Route::get('/companies/{company}/shipments', [CompanyController::class, 'shipments'])->name('companies.shipments');
    Route::get('/companies/{company}/performance', [CompanyController::class, 'performance'])->name('companies.performance');
    Route::patch('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');

    // ===== EMPLOYEE MANAGEMENT =====
    Route::resource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/performance', [EmployeeController::class, 'performance'])->name('employees.performance');
    Route::get('/employees/{employee}/shipments', [EmployeeController::class, 'shipments'])->name('employees.shipments');
    Route::patch('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::post('employees/{employee}/covenant', [EmployeeController::class, 'createCovenant'])->name('employees.create-covenant');

    // ===== PORTS MANAGEMENT =====
    Route::resource('ports', PortController::class);
    Route::patch('ports/{port}/toggle-status', [PortController::class, 'toggleStatus'])->name('ports.toggle-status');
    Route::get('ports/{port}/statistics', [PortController::class, 'statistics'])->name('ports.statistics');

    // ===== SHIPMENT TYPES MANAGEMENT =====
    Route::resource('shipment-types', ShipmentTypeController::class);
    Route::patch('shipment-types/{shipmentType}/toggle-status', [ShipmentTypeController::class, 'toggleStatus'])->name('shipment-types.toggle-status');
    Route::get('shipment-types/{shipmentType}/statistics', [ShipmentTypeController::class, 'statistics'])->name('shipment-types.statistics');

    // ===== SHIPPING AGENCIES MANAGEMENT =====
    Route::resource('shipping-agencies', ShippingAgencyController::class);
    Route::patch('shipping-agencies/{shippingAgency}/toggle-status', [ShippingAgencyController::class, 'toggleStatus'])->name('shipping-agencies.toggle-status');
    Route::get('shipping-agencies/{shippingAgency}/statistics', [ShippingAgencyController::class, 'statistics'])->name('shipping-agencies.statistics');

    // ======================================================================================
    // USER MANAGEMENT (Admin only)
    // ======================================================================================
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // ======================================================================================
    // FILE MANAGEMENT
    // ======================================================================================
    Route::prefix('file')->name('file.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::post('/export', [FileController::class, 'export'])->name('export');
        Route::post('/import', [FileController::class, 'import'])->name('import');
    });

    // ======================================================================================
    // SUBMENU MANAGEMENT (Navigation Only - No CRUD operations here)
    // ======================================================================================
    Route::prefix('submenu')->name('submenu.')->group(function () {
        Route::get('/', [SubmenuController::class, 'index'])->name('index');

        // Shipment Information (Display only - CRUD operations in main routes)
        Route::get('/ports', [SubmenuController::class, 'ports'])->name('ports');
        Route::get('/shipping-agency', [SubmenuController::class, 'agencies'])->name('agencies');
        Route::get('/shipment-types', [SubmenuController::class, 'types'])->name('types');

        // Quick form submissions (inline forms in submenu)
        Route::post('/ports', [SubmenuController::class, 'storePorts'])->name('ports.store');
        Route::post('/agencies', [SubmenuController::class, 'storeAgencies'])->name('agencies.store');
        Route::post('/types', [SubmenuController::class, 'storeTypes'])->name('types.store');

        // Other submenu items
        Route::get('/coo-types', [SubmenuController::class, 'cooTypes'])->name('coo-types');
        Route::get('/inspection-types', [SubmenuController::class, 'inspectionTypes'])->name('inspection-types');
        Route::get('/bosla-gomrok', [SubmenuController::class, 'boslaGomrok'])->name('bosla-gomrok');
        Route::get('/destinations', [SubmenuController::class, 'destinations'])->name('destinations');
        Route::get('/load-containers', [SubmenuController::class, 'loadContainers'])->name('load-containers');
        Route::get('/consignee-notify', [SubmenuController::class, 'consigneeNotify'])->name('consignee-notify');
        Route::get('/quantity-types', [SubmenuController::class, 'quantityTypes'])->name('quantity-types');
        Route::get('/shippers', [SubmenuController::class, 'shippers'])->name('shippers');
    });

    // ======================================================================================
    // ACCOUNTING SYSTEM
    // ======================================================================================
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

    // ======================================================================================
    // SETTINGS
    // ======================================================================================
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

    // ======================================================================================
    // API ROUTES FOR AJAX CALLS
    // ======================================================================================
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
        Route::get('/employees/department/{department}', [EmployeeController::class, 'getByDepartment'])->name('employees.by-department');

        // Ports API
        Route::get('/ports/active', [PortController::class, 'getActive'])->name('ports.active');

        // Shipment Types API
        Route::get('/shipment-types/active', [ShipmentTypeController::class, 'getActive'])->name('shipment-types.active');
        Route::get('/shipment-types/category/{category}', [ShipmentTypeController::class, 'getByCategory'])->name('shipment-types.by-category');

        // Shipping Agencies API
        Route::get('/shipping-agencies/active', [ShippingAgencyController::class, 'getActive'])->name('shipping-agencies.active');
        Route::get('/shipping-agencies/country/{country}', [ShippingAgencyController::class, 'getByCountry'])->name('shipping-agencies.by-country');
        Route::get('/shipping-agencies/search', [ShippingAgencyController::class, 'search'])->name('shipping-agencies.search');
    });

    // ======================================================================================
    // KEYBOARD SHORTCUTS MAPPING
    // ======================================================================================
    Route::prefix('quick')->name('quick.')->group(function () {
        // Ctrl+F1: Create new shipment
        Route::get('/shipment/create', [ShipmentController::class, 'create'])->name('shipment.create');

        // Ctrl+U: Users section
        Route::get('/users', [UserController::class, 'index'])->name('users');

        // Ctrl+E: Employee section
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');

        // Ctrl+G: Change password
        Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');

        // Alt+S: Service management
        Route::get('/services', [SubmenuController::class, 'services'])->name('services');
    });
});


Route::middleware(['auth'])->group(function () {
    
    // Role Management Routes
    Route::resource('roles', RoleController::class);
    Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    
    // Permission Management Routes
    Route::resource('permissions', PermissionController::class);
    Route::patch('permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    
    // Update existing user routes to use the new controller
    Route::resource('users', UpdatedUserController::class);
    
});

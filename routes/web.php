<?php

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
use App\Http\Controllers\PortController;
use App\Http\Controllers\ShipmentTypeController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountingDashboardController;
use App\Http\Controllers\Accounting\JobController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\COOTypeController;
use App\Http\Controllers\ShippingAgencyController;
use App\Http\Controllers\BoslaGomrokController;
use App\Http\Controllers\ConsigneeNotifyController;
use App\Http\Controllers\ContainerLoadingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\QuantityTypeController;

// ======================================================================================
// GUEST ROUTES
// ======================================================================================
Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ======================================================================================
// PUBLIC ROUTES
// ======================================================================================
Route::get('/track/{shipmentId}', [ShipmentController::class, 'track'])->name('shipments.track');

// ======================================================================================
// AUTHENTICATED ROUTES
// ======================================================================================
Route::middleware('auth')->group(function () {

    // ===== DASHBOARD & LOGOUT =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ===== CORE RESOURCE MANAGEMENT =====
    Route::resource('shipments', ShipmentController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);

    // // ===== USER MANAGEMENT (Integrated: Users + Roles + Permissions) =====
    // Route::middleware('permission:users.view')->prefix('users')->group(function () {
    //     // Main user management
    //     Route::get('/', [UserController::class, 'index'])->name('users.index');
    //     Route::post('/', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
    //     Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    //     Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:users.edit');
    //     Route::put('/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
    //     Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');

    //     // Integrated role management
    //     Route::middleware('permission:roles.create')->group(function () {
    //         Route::post('/roles', [UserController::class, 'storeRole'])->name('users.roles.store');
    //     });
    //     Route::middleware('permission:roles.delete')->group(function () {
    //         Route::delete('/roles/{role}', [UserController::class, 'destroyRole'])->name('users.roles.destroy');
    //     });

    //     // Integrated permission management
    //     Route::middleware('permission:roles.manage-permissions')->group(function () {
    //         Route::post('/permissions', [UserController::class, 'storePermission'])->name('users.permissions.store');
    //         Route::delete('/permissions/{permission}', [UserController::class, 'destroyPermission'])->name('users.permissions.destroy');
    //     });
    // });
    Route::prefix('users')->name('users.')->group(function () {
        // Main user CRUD
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // 🔥 ROLE MANAGEMENT (INTEGRATED)
        Route::post('/roles', [UserController::class, 'storeRole'])->name('roles.store');
        Route::delete('/roles/{role}', [UserController::class, 'destroyRole'])->name('roles.destroy');
        Route::get('/roles/{role}', [UserController::class, 'getRole'])->name('roles.get');

        // 🔥 PERMISSION MANAGEMENT (INTEGRATED)  
        Route::post('/permissions', [UserController::class, 'storePermission'])->name('permissions.store');
        Route::delete('/permissions/{permission}', [UserController::class, 'destroyPermission'])->name('permissions.destroy');
        Route::get('/permissions/{permission}', [UserController::class, 'getPermission'])->name('permissions.get');

        // 🔥 USER AJAX ENDPOINTS
        Route::get('/get-user/{user}', [UserController::class, 'getUser'])->name('get-user');
    });

    // ===== STANDALONE ROUTES (OPTIONAL) =====
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // ===== OTHER ROUTES =====
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');
        Route::post('/change-password', [SettingsController::class, 'updatePassword'])->name('update-password');
    });


    // ===== SHIPMENT EXTENDED ACTIONS =====
    Route::prefix('shipments')->name('shipments.')->group(function () {
        Route::patch('/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('update-status');
        Route::get('/{shipment}/document/{type}', [ShipmentController::class, 'generateDocument'])->name('document');
        Route::post('/{shipment}/duplicate', [ShipmentController::class, 'duplicate'])->name('duplicate');
        Route::patch('/{shipment}/toggle-archive', [ShipmentController::class, 'toggleArchive'])->name('toggle-archive');
    });

    // ===== COMPANY EXTENDED ACTIONS =====
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/{company}/shipments', [CompanyController::class, 'shipments'])->name('shipments');
        Route::get('/{company}/performance', [CompanyController::class, 'performance'])->name('performance');
        Route::patch('/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ===== EMPLOYEE EXTENDED ACTIONS =====
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/{employee}/performance', [EmployeeController::class, 'performance'])->name('performance');
        Route::get('/{employee}/shipments', [EmployeeController::class, 'shipments'])->name('shipments');
        Route::patch('/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{employee}/covenant', [EmployeeController::class, 'createCovenant'])->name('create-covenant');
    });

    // ===== SUBMENU CONFIGURATION =====
    Route::prefix('submenu')->name('submenu.')->group(function () {
        Route::get('/', [SubmenuController::class, 'index'])->name('index');

        Route::resource('ports', PortController::class, [
            'names' => [
                'index' => 'ports.index',
                'create' => 'ports.create',
                'store' => 'ports.store',
                'show' => 'ports.show',
                'edit' => 'ports.edit',
                'update' => 'ports.update',
                'destroy' => 'ports.destroy'
            ]
        ]);
        Route::resource('quantity-type', QuantityTypeController::class, [
            'names' => [
                'index' => 'quantity-types.index',
                'create' => 'quantity-types.create',
                'store' => 'quantity-types.store',
                'show' => 'quantity-types.show',
                'edit' => 'quantity-types.edit',
                'update' => 'quantity-types.update',
                'destroy' => 'quantity-types.destroy'
            ]
        ]);
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

        Route::resource('consignee-notify', ConsigneeNotifyController::class, [
            'names' => [
                'index' => 'consignee-notify.index',
                'create' => 'consignee-notify.create',
                'store' => 'consignee-notify.store',
                'show' => 'consignee-notify.show',
                'edit' => 'consignee-notify.edit',
                'update' => 'consignee-notify.update',
                'destroy' => 'consignee-notify.destroy'
            ]
        ]);
        // Consignee & Notify Parties Resource Routes
        Route::resource('consignee-notify', ConsigneeNotifyController::class, [
            'names' => [
                'index' => 'consignee-notify.index',
                'create' => 'consignee-notify.create',
                'store' => 'consignee-notify.store',
                'show' => 'consignee-notify.show',
                'edit' => 'consignee-notify.edit',
                'update' => 'consignee-notify.update',
                'destroy' => 'consignee-notify.destroy'
            ]
        ]);

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
        Route::resource('bosla-gomrok', BoslaGomrokController::class, [
            'names' => [
                'index' => 'bosla-gomrok.index',
                'create' => 'bosla-gomrok.create',
                'store' => 'bosla-gomrok.store',
                'show' => 'bosla-gomrok.show',
                'edit' => 'bosla-gomrok.edit',
                'update' => 'bosla-gomrok.update',
                'destroy' => 'bosla-gomrok.destroy'
            ]
        ]);


        // COO Types Resource Routes
        Route::resource('coo-types', COOTypeController::class, [
            'names' => [
                'index' => 'coo-types.index',
                'create' => 'coo-types.create',
                'store' => 'coo-types.store',
                'show' => 'coo-types.show',
                'edit' => 'coo-types.edit',
                'update' => 'coo-types.update',
                'destroy' => 'coo-types.destroy'
            ]
        ]);
        // Shipment Information Management
        // Route::get('/ports', [SubmenuController::class, 'ports'])->name('ports');
        // Route::get('/shipping-agency', [SubmenuController::class, 'agencies'])->name('agencies');
        // Route::get('/shipment-types', [SubmenuController::class, 'types'])->name('types');

        // Quick submissions
        // Route::post('/ports', [SubmenuController::class, 'storePorts'])->name('ports.store');
        // Route::post('/agencies', [SubmenuController::class, 'storeAgencies'])->name('agencies.store');
        // Route::post('/types', [SubmenuController::class, 'storeTypes'])->name('types.store');

        // Other submenu items

        // Route::get('/inspection-types', [SubmenuController::class, 'inspectionTypes'])->name('inspection-types');
        // Route::get('/bosla-gomrok', [SubmenuController::class, 'boslaGomrok'])->name('bosla-gomrok');
        // Route::get('/destinations', [SubmenuController::class, 'destinations'])->name('destinations');
        // Route::get('/load-containers', [SubmenuController::class, 'loadContainers'])->name('load-containers');
        // Route::get('/consignee-notify', [SubmenuController::class, 'consigneeNotify'])->name('consignee-notify');
        // Route::get('/quantity-types', [SubmenuController::class, 'quantityTypes'])->name('quantity-types');
        // Route::get('/shippers', [SubmenuController::class, 'shippers'])->name('shippers');
    });

    // ===== MASTER DATA MANAGEMENT =====
    Route::middleware('permission:settings.manage-ports')->group(function () {
        Route::resource('ports', PortController::class);
        Route::patch('/ports/{port}/toggle-status', [PortController::class, 'toggleStatus'])->name('ports.toggle-status');
        Route::get('/ports/{port}/statistics', [PortController::class, 'statistics'])->name('ports.statistics');
    });

    Route::middleware('permission:settings.manage-types')->group(function () {
        Route::resource('shipment-types', ShipmentTypeController::class);
        Route::patch('/shipment-types/{shipmentType}/toggle-status', [ShipmentTypeController::class, 'toggleStatus'])->name('shipment-types.toggle-status');
        Route::get('/shipment-types/{shipmentType}/statistics', [ShipmentTypeController::class, 'statistics'])->name('shipment-types.statistics');
    });

    Route::middleware('permission:settings.manage-agencies')->group(function () {
        Route::resource('shipping-agencies', ShippingAgencyController::class);
        Route::patch('/shipping-agencies/{shippingAgency}/toggle-status', [ShippingAgencyController::class, 'toggleStatus'])->name('shipping-agencies.toggle-status');
        Route::get('/shipping-agencies/{shippingAgency}/statistics', [ShippingAgencyController::class, 'statistics'])->name('shipping-agencies.statistics');
    });

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

    // ===== FILE MANAGEMENT =====
    Route::middleware('permission:files.view')->prefix('file')->name('file.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::post('/export', [FileController::class, 'export'])->name('export')->middleware('permission:reports.export');
        Route::post('/import', [FileController::class, 'import'])->name('import')->middleware('permission:files.upload');
    });

    // ===== SETTINGS =====
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/preferences', [SettingsController::class, 'updatePreferences'])->name('update-preferences');
        Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('update-notifications');

        // Change Password
        Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');
        Route::post('/change-password', [SettingsController::class, 'updatePassword'])->name('update-password');

        // System Settings (Admin only)
        Route::middleware('permission:settings.edit-system')->group(function () {
            Route::get('/system', [SettingsController::class, 'systemSettings'])->name('system');
            Route::post('/system', [SettingsController::class, 'updateSystemSettings'])->name('update-system');
        });
    });

    // ===== STANDALONE ROLE & PERMISSION MANAGEMENT (Optional - Direct Access) =====
    Route::middleware('permission:roles.view')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::patch('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    });

    Route::middleware('permission:roles.manage-permissions')->group(function () {
        Route::resource('permissions', PermissionController::class);
        Route::patch('/permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    });

    // ===== KEYBOARD SHORTCUTS =====
    Route::prefix('quick')->name('quick.')->group(function () {
        Route::get('/shipment/create', [ShipmentController::class, 'create'])->name('shipment.create'); // Ctrl+F1
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees'); // Ctrl+E
        Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change-password'); // Ctrl+G
        Route::get('/services', [SubmenuController::class, 'services'])->name('services'); // Alt+S
    });

    // ===== API ENDPOINTS =====
    Route::prefix('api')->name('api.')->group(function () {

        // Dashboard
        Route::get('/dashboard-metrics', [DashboardController::class, 'getMetrics'])->name('dashboard.metrics');

        // Shipments
        Route::get('/shipments/search', [ShipmentController::class, 'search'])->name('shipments.search');
        Route::get('/shipments/{shipment}/tracking', [ShipmentController::class, 'getTracking'])->name('shipments.tracking');

        // Companies
        Route::get('/companies/search', [CompanyController::class, 'search'])->name('companies.search');
        Route::get('/companies/{company}/metrics', [CompanyController::class, 'getMetrics'])->name('companies.metrics');

        // Employees
        Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
        Route::get('/employees/{employee}/metrics', [EmployeeController::class, 'getMetrics'])->name('employees.metrics');
        Route::get('/employees/department/{department}', [EmployeeController::class, 'getByDepartment'])->name('employees.by-department');

        // Master Data
        Route::get('/ports/active', [PortController::class, 'getActive'])->name('ports.active');
        Route::get('/shipment-types/active', [ShipmentTypeController::class, 'getActive'])->name('shipment-types.active');
        Route::get('/shipment-types/category/{category}', [ShipmentTypeController::class, 'getByCategory'])->name('shipment-types.by-category');
        Route::get('/shipping-agencies/active', [ShippingAgencyController::class, 'getActive'])->name('shipping-agencies.active');
        Route::get('/shipping-agencies/country/{country}', [ShippingAgencyController::class, 'getByCountry'])->name('shipping-agencies.by-country');
        Route::get('/shipping-agencies/search', [ShippingAgencyController::class, 'search'])->name('shipping-agencies.search');
    });
});

// ======================================================================================
// DEVELOPMENT ROUTES (Remove in production)
// ======================================================================================
if (app()->environment('local')) {
    Route::get('/dev/reset-permissions', function () {
        Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);
        Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
        return 'Permissions and roles reset successfully!';
    });
}

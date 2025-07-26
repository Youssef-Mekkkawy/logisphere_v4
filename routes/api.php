<?php

// ===== API ENDPOINTS =====

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Management\ShipmentController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Route;
// Route::prefix('api')->name('api.')->group(function () {

//     // Dashboard
//     Route::get('/dashboard-metrics', [DashboardController::class, 'getMetrics'])->name('dashboard.metrics');

//     // Shipments
//     Route::get('/shipments/search', [ShipmentController::class, 'search'])->name('shipments.search');
//     Route::get('/shipments/{shipment}/tracking', [ShipmentController::class, 'getTracking'])->name('shipments.tracking');

//     // Companies
//     Route::get('/companies/search', [CompanyController::class, 'search'])->name('companies.search');
//     Route::get('/companies/{company}/metrics', [CompanyController::class, 'getMetrics'])->name('companies.metrics');

//     // Employees
//     Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
//     Route::get('/employees/{employee}/metrics', [EmployeeController::class, 'getMetrics'])->name('employees.metrics');
//     Route::get('/employees/department/{department}', [EmployeeController::class, 'getByDepartment'])->name('employees.by-department');

//     // Master Data
//     Route::get('/ports/active', [PortController::class, 'getActive'])->name('ports.active');
//     Route::get('/shipment-types/active', [ShipmentTypeController::class, 'getActive'])->name('shipment-types.active');
//     Route::get('/shipment-types/category/{category}', [ShipmentTypeController::class, 'getByCategory'])->name('shipment-types.by-category');
//     Route::get('/shipping-agencies/active', [ShippingAgencyController::class, 'getActive'])->name('shipping-agencies.active');
//     Route::get('/shipping-agencies/country/{country}', [ShippingAgencyController::class, 'getByCountry'])->name('shipping-agencies.by-country');
//     Route::get('/shipping-agencies/search', [ShippingAgencyController::class, 'search'])->name('shipping-agencies.search');
// });

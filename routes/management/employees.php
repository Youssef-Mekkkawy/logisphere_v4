<?php

use App\Http\Controllers\Management\EmployeeController;
use Illuminate\Support\Facades\Route;
// ===== CORE RESOURCE MANAGEMENT =====

Route::resource('employees', EmployeeController::class);
// ===== EMPLOYEE EXTENDED ACTIONS =====
Route::prefix('employees')->name('employees.')->group(function () {
    Route::get('/{employee}/performance', [EmployeeController::class, 'performance'])->name('performance');
    Route::get('/{employee}/shipments', [EmployeeController::class, 'shipments'])->name('shipments');
    Route::patch('/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{employee}/covenant', [EmployeeController::class, 'createCovenant'])->name('create-covenant');
});
// ===== API ENDPOINTS =====
Route::prefix('api')->name('api.')->group(function () {
    // Employees
    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    Route::get('/employees/{employee}/metrics', [EmployeeController::class, 'getMetrics'])->name('employees.metrics');
    Route::get('/employees/department/{department}', [EmployeeController::class, 'getByDepartment'])->name('employees.by-department');
});

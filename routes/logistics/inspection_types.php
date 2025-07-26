<?php

use App\Http\Controllers\logistics\InspectionTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('inspection-types', InspectionTypeController::class, [
    'names' => [
        'index' => 'inspection-types.index',
        'create' => 'inspection-types.create',
        'store' => 'inspection-types.store',
        'show' => 'inspection-types.show',
        'edit' => 'inspection-types.edit',
        'update' => 'inspection-types.update',
        'destroy' => 'inspection-types.destroy'
    ]
]);

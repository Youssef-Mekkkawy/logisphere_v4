<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Logistics\COOTypeController;

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

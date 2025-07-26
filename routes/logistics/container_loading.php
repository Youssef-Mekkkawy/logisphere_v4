<?php

use App\Http\Controllers\logistics\ContainerLoadingController;
use Illuminate\Support\Facades\Route;

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

<?php

use App\Http\Controllers\logistics\DestinationController;
use Illuminate\Support\Facades\Route;


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

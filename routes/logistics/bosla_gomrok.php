<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Logistics\BoslaGomrokController;

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

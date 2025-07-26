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
// Additional Bosla from Gomrok API routes (optional)
Route::prefix('bosla-gomrok')->name('bosla-gomrok.')->group(function () {
    Route::get('/{boslaGomrok}/statistics', [BoslaGomrokController::class, 'statistics'])->name('statistics');
    Route::post('/{boslaGomrok}/toggle-status', [BoslaGomrokController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/active', [BoslaGomrokController::class, 'getActive'])->name('active');
    Route::get('/by-office/{office}', [BoslaGomrokController::class, 'getByOffice'])->name('by-office');
    Route::get('/by-type/{type}', [BoslaGomrokController::class, 'getByType'])->name('by-type');
});

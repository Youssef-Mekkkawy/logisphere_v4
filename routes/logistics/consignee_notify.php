<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistics\ConsigneeNotifyController;

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

Route::get('consignee-notify/api/by-type', [ConsigneeNotifyController::class, 'getByType'])->name('consignee-notify.by-type');
Route::patch('consignee-notify/{consigneeNotify}/toggle-status', [ConsigneeNotifyController::class, 'toggleStatus'])->name('consignee-notify.toggle-status');

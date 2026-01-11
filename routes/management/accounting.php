<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounting\AccountingDashboardController;

Route::resource('account', AccountingDashboardController::class);

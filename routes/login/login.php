<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// ======================================================================================
// GUEST ROUTES
// ======================================================================================
Route::get('/', [LoginController::class, 'login']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::get('/', fn() => redirect()->route('login'));

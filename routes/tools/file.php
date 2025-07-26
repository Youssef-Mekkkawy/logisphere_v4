<?php

use App\Http\Controllers\Management\FileController;
use Illuminate\Support\Facades\Route;

// ===== FILE MANAGEMENT =====
Route::prefix('file')->name('file.')->group(function () {
    Route::get('/', [FileController::class, 'index'])->name('index');
    Route::post('/export', [FileController::class, 'export'])->name('export')->middleware('permission:reports.export');
    Route::post('/import', [FileController::class, 'import'])->name('import')->middleware('permission:files.upload');
});

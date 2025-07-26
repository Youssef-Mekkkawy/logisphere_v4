<?php

use Illuminate\Support\Facades\Route;


Route::get('error-404', fn() => response()->view('shared.errors.404', [], 404));

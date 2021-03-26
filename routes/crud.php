<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudController;

if (config('crud.enabled')) {
    Route::post('/crud/data', [CrudController::class, 'data']);
    Route::post('/crud/truncate', [CrudController::class, 'truncate']);
    Route::resource('/crud', CrudController::class);
}

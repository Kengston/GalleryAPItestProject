<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::middleware('api')->group(function () {
    Route::post('images', [ImageController::class, 'upload']);
    Route::get('images/{id}', [ImageController::class, 'view']);
});

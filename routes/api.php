<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function() {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::put('/{order}', [OrderController::class, 'update']);
});
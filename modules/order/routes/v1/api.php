<?php


use Modules\order\app\Http\v1\OrderController;

Route::controller(OrderController::class)->middleware('auth:sanctum')->prefix('order')->group(function () {
    Route::post('buy', 'buy');
    Route::post('sell', 'sell');
});

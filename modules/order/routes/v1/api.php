<?php


use Modules\order\app\Http\Controllers\Api\v1\OrderController;

Route::controller(OrderController::class)->middleware('auth:sanctum')->prefix('order')->group(function () {
    Route::post('buy', 'buy');
    Route::post('sell', 'sell');
    Route::get('history', 'history');
    Route::post('cancel', 'cancel');
});

Route::get('test-redis', function(){
    Cache::store('redis')->put('test-key', ['foo' => 'bar'], 60);
});

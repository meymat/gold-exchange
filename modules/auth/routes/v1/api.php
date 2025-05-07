<?php


use Illuminate\Support\Facades\Route;
use Modules\auth\app\Http\Controllers\Api\v1\AuthController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});


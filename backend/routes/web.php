<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::any('/login-email-validate', [AuthController::class, 'validateCredentials']);
    Route::any('/check-login', [AuthController::class, 'checkLogin']);
});
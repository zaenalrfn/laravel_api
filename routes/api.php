<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// route auth
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});


Route::apiResource('posts', 'App\Http\Controllers\Api\PostController');

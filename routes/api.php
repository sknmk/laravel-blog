<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::post('/login', [App\Http\Controllers\Auth\Api::class, 'login']);
    Route::post('/register', [App\Http\Controllers\Auth\Api::class, 'register']);
});

Route::middleware('auth:api')->get('/ping', [App\Http\Controllers\Auth\Api::class, 'ping']);

Route::group(['prefix' => 'post', 'middleware' => 'auth:api'], function () {
    Route::get('/get', [App\Http\Controllers\ApiControllers\PostController::class, 'get']);
    Route::get('/dashboard', [App\Http\Controllers\ApiControllers\PostController::class, 'dashboard']);
    Route::get('/show/{post}', [App\Http\Controllers\ApiControllers\PostController::class, 'show']);
    Route::post('/create', [App\Http\Controllers\ApiControllers\PostController::class, 'store']);
    Route::put('/update/{post}', [App\Http\Controllers\ApiControllers\PostController::class, 'update']);
    Route::delete('/delete/{post}', [App\Http\Controllers\ApiControllers\PostController::class, 'destroy']);
});


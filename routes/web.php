<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/dashboard', [\App\Http\Controllers\PostController::class, 'dashboard'])->name('posts.dashboard');
Route::resource('posts', \App\Http\Controllers\PostController::class);

require __DIR__.'/auth.php';

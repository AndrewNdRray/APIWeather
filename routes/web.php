<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/auth/google', [App\Http\Controllers\HomeController::class, 'googleRedirect'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\HomeController::class, 'loginWithGoogle']);
Route::get('/weather', [App\Http\Controllers\HomeController::class, 'getWeather'])->name('weather');


//Route::get('/callback', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

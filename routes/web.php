<?php

use Illuminate\Support\Facades\Route;
use  app\Http\Controllers\Auth\OAuthContoller;


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

Route::view('/', 'auth.login');
Route::view('/register', 'auth.register');
Route::post('login', 'AuthController@login')->name('login');
Route::post('register', 'AuthController@register');
Route::post('login', 'OAuthController@login')->name('oauth-login');

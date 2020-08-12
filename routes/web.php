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

Route::middleware(['guest'])->group(function () {
    Route::view('/', 'auth.login')->name('login');
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register');
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});
Route::group(['middleware' => 'auth'], function () {
    Route::view('/home', 'dashboard.main');
    Route::get('/packages/general', 'Packages\Controller@index');
    Route::get('/records/general', 'Records\Controller@index');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/workers-table', 'ClientController@index');
    Route::get('logout', 'AuthController@logout');
});

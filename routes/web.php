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
    // Route::group([], function () {
    Route::view('/home', 'dashboard.main');
    Route::prefix("packages")->group(function () {
        Route::get('/general', 'Packages\Controller@index');
        Route::post('/', 'Packages\Controller@store');
        Route::put('/{package_id}', 'Packages\Controller@update');
        Route::get('/by/locker', 'Packages\Controller@packagesByLocker');
    });
    Route::prefix("requests")->group(function () {
        Route::get('/general', 'Requests\Controller@index');
        Route::post('/', 'Requests\Controller@store');
        Route::put('/{request_id}', 'Requests\Controller@update');
    });

    Route::get('/records/general', 'Records\Controller@index');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/profile/{id}', 'ProfileController@show');
    Route::put('/profiles','ProfileController@updateCredentials');
    Route::put('/profile/password','ProfileController@updatePassword');

    Route::get('/workers', 'WorkerController@index');
    Route::get('/workers/{user_id}/profile', 'WorkerController@show');
    Route::post('/workers/{user_id}/accept', 'WorkerController@processWorker');

    Route::get('/roles', 'UserRoleController@index')->name('roles');
    Route::post('/roles', 'UserRoleController@store');
    Route::put('/role/{id}', 'UserRoleController@updatePermissions');
    Route::delete('/role/{id}', 'UserRoleController@delete');
    Route::put('/role/{role_id}/menu/{menu_id}', 'UserRoleController@updatePermission');
    //Route::put('/roles/{userId}',"UserRoleController@update");

    Route::get('/clients','ClientController@index')->name('clients');
    Route::post('/clients','ClientController@create');
    Route::put('/client/{id}', 'ClientController@update');
    Route::get('/lots', 'LotsController@index')->name('lots');
    Route::get('logout', 'AuthController@logout');
});

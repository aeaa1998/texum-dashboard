<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('oauth', 'OAuthController@login');
    Route::post('register', 'AuthController@register');
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', 'OAuthController@logout');
    Route::post('/clients/create','ClientController@create');
    Route::get('/clients','ClientController@index');
    Route::get('/clients/{id}','ClientController@show');
    Route::put('/client/{id}','ClientController@update');
    Route::delete('/client/{id}','ClientController@delete');
    Route::get('/profiles','ProfileController@index');

    Route::get('/roles','RoleController@index');
    Route::get('/roles/{id}','RoleController@show');
    Route::put('/roles/{id}','RoleController@update');
    Route::delete('/roles/{id}','RoleController@delete');
    Route::post('/roles/create','RoleController@create');
    Route::get('/UserRole','UserRoleController@index');

    Route::get('/UserRole/{id}','UserRoleController@show');
    Route::put('/UserRole/{id}','UserRoleController@update');
    Route::delete('/UserRole/{id}','UserRoleController@delete');
    Route::post('/UserRole/create','UserRoleController@create');

    //Route::get('/profiles/{id}','ProfileController@show');
    //Route::put('/profile/{id}','ProfileController@update');
});




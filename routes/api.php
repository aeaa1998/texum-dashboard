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
});


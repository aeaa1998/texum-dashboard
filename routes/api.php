<?php

use Illuminate\Support\Facades\Route;

// Route::group(['prefix' => 'auth'], function () {
Route::post('oauth/login', 'Auth\OAuthController@login');
Route::post('register', 'AuthController@register');
// });
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', 'OAuthController@logout');
});

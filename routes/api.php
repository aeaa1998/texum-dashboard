<?php
use Illuminate\Http\Request;
use  app\Http\Controllers\Auth\OAuthContoller;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('oauth-login', 'OAuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        //Route::get('oauth-logout', 'OAuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});
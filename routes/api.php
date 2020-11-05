<?php

use Illuminate\Support\Facades\Route;

// Route::group(['prefix' => 'auth'], function () {
Route::post('oauth/login', 'Auth\OAuthController@login');
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
// });

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['namespace' => 'API'], function () {
        Route::get('/user', 'UserController@index');
        Route::get('/clients', 'ClientsController@index');
        Route::get('/client/{id}/lots', 'ClientsController@lots');
        Route::post('packages', 'PackagesController@store');
    });
    Route::get('logout', 'OAuthController@logout');

    Route::post('/clients', 'ClientController@create');
    // Route::get('/clients', 'ClientController@index');
    Route::get('/clients/{id}', 'ClientController@show');
    Route::put('/client/{id}', 'ClientController@update');
    Route::delete('/client/{id}', 'ClientController@delete');


    //Route::get('/profiles/{id}','ProfileController@show');
    //Route::put('/profile/{id}','ProfileController@update');

    Route::get('/lots', 'LotsController@index');
    Route::get('/lots/{id}', 'LotsController@show');
    Route::post('lots', 'LotsController@create');
    Route::put('lot/{id}', 'LotsController@update');
    Route::delete('lot/{id}', 'LotsController@delete');

    Route::get('/roles', 'RoleController@index');
    Route::get('/roles/{id}', 'RoleController@show');
    Route::put('/roles/{id}', 'RoleController@update');
    Route::delete('/roles/{id}', 'RoleController@delete');
    Route::post('/roles', 'RoleController@create');

    Route::get('/user/role', 'UserRoleController@index');
    Route::get('/user/role/{id}', 'UserRoleController@show');
    Route::put('/user/{user_id}/role', 'UserRoleController@update');
    Route::delete('/user/role/{id}', 'UserRoleController@delete');
    Route::post('/user/role', 'UserRoleController@create');

    Route::get('/records/{worker_id}', 'RecordsController@getRecordsByUserId');

    Route::get('/requests', 'RequestsController@index');
    Route::get('/request/{id}', 'RequestsController@show');
    Route::post('/requests', 'RequestsController@create');
    Route::put('/requests/{id}', 'RequestsController@update');
    Route::delete('/requests/{id}', 'RequestsController@delete');

    Route::put('/user/{user_id}/password', 'UserPasswordController@update');
    //Route::get('/user/{user_id}/password', 'UserPasswordController@index');

    #Route::get('/lots/{id}/deliver', 'LotsController@deliverLot');
});

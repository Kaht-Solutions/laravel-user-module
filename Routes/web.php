<?php

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

Route::group(['middleware' => ['web', 'auth', 'clearcache', 'permission_check'], 'prefix' => '/admin'], function () {

    Route::get('/', 'UserController@adminindex');

});

Route::group(['middleware' => ['web', 'auth', 'clearcache', 'permission_check'], 'prefix' => '/admin/user'], function () {

    Route::get('/', 'UserController@index');
    Route::get('show', 'UserController@show');
    Route::get('create', 'UserController@create');
    Route::get('edit', 'UserController@edit');
    Route::get('destroy', 'UserController@destroy');

    Route::post('store', 'UserController@store');
    Route::post('update', 'UserController@update');

    Route::post('setrole', 'UserController@setrole');
    Route::post('setpermission', 'UserController@setpermission');

    Route::get('/generate', 'UserController@generate_permissions');

    Route::get('/passport', 'UserController@passport');

    Route::group(['prefix' => 'role'], function () {

        Route::get('/', 'RoleController@index');
        Route::get('/show', 'RoleController@show');
        Route::get('/create', 'RoleController@create');
        Route::get('/edit', 'RoleController@edit');
        Route::get('/destroy', 'RoleController@destroy');

        Route::post('/store', 'RoleController@store');
        Route::post('/update', 'RoleController@update');

    });

    Route::group(['prefix' => 'permission'], function () {

        Route::get('/', 'PermissionController@index');
        Route::get('/show', 'PermissionController@show');
        Route::get('/create', 'PermissionController@create');
        Route::get('/edit', 'PermissionController@edit');
        Route::get('/destroy', 'PermissionController@destroy');

        Route::post('/store', 'PermissionController@store');
        Route::post('/update', 'PermissionController@update');

    });

});

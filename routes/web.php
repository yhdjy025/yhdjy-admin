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

Route::get('/error', 'HomeController@err');
Auth::routes();

Route::get('/', 'Home\IndexController@index')->middleware(['auth', 'permission'])->name('index');

Route::group(['middleware' => ['auth', 'permission'], 'prefix' => 'home', 'namespace' => 'Home'], function () {
    //Route::get('index', 'IndexController@index')->name('index');
});

Route::group(['middleware' => ['auth', 'permission'], 'prefix' => 'user', 'namespace' => 'User'], function () {
    Route::match(['get', 'post'], 'user/index', 'UserController@index');
    Route::post('user/edit', 'UserController@edit');
    Route::post('user/save', 'UserController@save');
    Route::post('user/delete', 'UserController@delete');

    Route::match(['get', 'post'], 'role/index', 'RoleController@index');
    Route::post('role/add', 'RoleController@add');
    Route::post('role/edit', 'RoleController@edit');
    Route::post('role/save', 'RoleController@save');
    Route::post('role/delete', 'RoleController@delete');
    Route::match(['get', 'post'], 'role/node', 'RoleController@node');
});

Route::group(['middleware' => ['auth', 'permission'], 'prefix' => 'system', 'namespace' => 'System'], function () {
    Route::post('public/upload', 'PublicController@upload');

    Route::match(['get', 'post'], 'dict/index', 'DictController@index');
    Route::post('dict/add', 'DictController@add');
    Route::post('dict/edit', 'DictController@edit');
    Route::post('dict/save', 'DictController@save');
    Route::post('dict/delete', 'DictController@delete');

    Route::match(['get', 'post'], 'dict/dictvalue', 'DictController@dictvalue');
    Route::post('dict/addvalue', 'DictController@addValue');
    Route::post('dict/editValue', 'DictController@editValue');
    Route::post('dict/savevalue', 'DictController@saveValue');
    Route::post('dict/deletevalue', 'DictController@deleteValue');

    Route::match(['get', 'post'], 'menu/index', 'MenuController@index');
    Route::post('menu/add', 'MenuController@add');
    Route::post('menu/edit', 'MenuController@edit');
    Route::post('menu/save', 'MenuController@save');
    Route::post('menu/delete', 'MenuController@delete');

    Route::get('node/index', 'NodeController@index');
    Route::post('node/save', 'NodeController@save');
});

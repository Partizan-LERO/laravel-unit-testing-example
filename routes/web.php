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



Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::middleware('auth')->group(function () {
    Route::get('/','ItemController@index')->name('index-item');
    Route::get('/item/show/{id}','ItemController@show')->name('show-item');
    Route::get('/item/create','ItemController@create')->name('create-item');
    Route::post('/item/store','ItemController@store')->name('store-item');
    Route::get('/item/edit/{id}','ItemController@edit')->name('edit-item');
    Route::patch('/item/update/{id}','ItemController@update')->name('update-item');
    Route::delete('/item/delete/{id}','ItemController@delete')->name('delete-item');
});

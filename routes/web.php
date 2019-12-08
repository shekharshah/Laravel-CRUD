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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=>'user'],function(){
	Route::any('index','YajraController@index')->name('index');
	Route::any('index/display','YajraController@datatable')->name('getDataTable');
	Route::any('register','YajraController@create')->name('register');
	Route::any('validatesdfsdf','YajraController@check_name_exists')->name('checkNameExists');//
	Route::any('store','YajraController@store')->name('store');
});

Route::any('edit/{id}','YajraController@edit')->name('edit');
Route::any('view_profile/{id}','YajraController@view_record')->name('view_record');
Route::any('update/{id}','YajraController@update')->name('update_record');
Route::any('delete/{id}','YajraController@destroy')->name('delete_record');


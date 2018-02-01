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

Route::get('404', function () {
    return view('404');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::post('createBook', 'HomeController@create')->name('createBook');

Route::get('book/{id}', 'HomeController@openBook')->name('openBook');

Route::post('deleteBook', 'HomeController@deleteBook')->name('deleteBook');

Route::post('bookPages', 'HomeController@bookPages')->name('bookPages');

Route::post('createPage', 'HomeController@createPage')->name('createPage');

Route::post('uploadImage', 'HomeController@uploadImage')->name('uploadImage');

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




Route::group(['middleware' => 'web'], function() {
    Route::get('auth/verify/{token}', 'Auth\LoginController@verify');
    Route::get('auth/send-verification', 'Auth\LoginController@sendVerification');

    Route::get('/', 'GuestController@index');
    Auth::routes();
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('settings/profile', 'SettingsController@profile');
    Route::get('settings/profile/edit', 'SettingsController@editProfile');
    Route::post('settings/profile', 'SettingsController@updateProfile');
    Route::get('settings/password', 'SettingsController@editPassword');
    Route::post('settings/password', 'SettingsController@updatePassword');
    
    Route::get('books/{book}/borrow', [
        'middleware' => ['auth', 'role:member'],
        'as' => 'books.borrow',
        'uses' => 'BooksController@borrow'
    ]);
    Route::put('books/{book}/return', [
        'middleware' => ['auth', 'role:member'],
        'as' => 'books.return',
        'uses' => 'BooksController@returnBack'
    ]);

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function() {
        Route::resource('authors', 'AuthorsController');
        Route::resource('books', 'BooksController');
        
        Route::resource('members', 'MembersController', [
            'only' => ['index', 'show', 'destroy']
        ]);

        Route::get('statistics', [
            'as'=>'statistics.index',
            'uses'=>'StatisticsController@index'
        ]);
    });
    
});

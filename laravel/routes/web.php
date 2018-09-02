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

Route::get('/', 'PagesController@home');



Route::resource('posts', 'PostsController');

Auth::routes();

Route::resource('preferences', 'DetailsController');

Route::get('/dashboard', 'DashboardController@index');

Route::get('matches', 'MatchController@index');

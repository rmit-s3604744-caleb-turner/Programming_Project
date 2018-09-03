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

Route::get('matchlist', 'MatchController@index');
Route::get('matches', 'MatchController@matches');
Route::post('sendRequest', 'MatchController@sendFriendRequest');
Route::post('acceptRequest', 'MatchController@acceptFriendRequest');
Route::post('denyRequest', 'MatchController@denyFriendRequest');

Route::group(['prefix' => 'messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});
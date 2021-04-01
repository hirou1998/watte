<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/err/unauthorized', function(){
    abort(401, 'Unauthorized');
})->name('unauthorized');
Route::get('/err/forbidden', function(){
    abort(403, 'Forbidden');
})->name('forbidden');
Route::get('/err/servererr', function(){
    abort(500, 'Server Error');
})->name('servererror');
Route::get('/err/notfound', function(){
    abort(404, 'Not Found');
})->name('notfound');

Route::post('/auth/user-and-group', 'Auth\LineFriendGroupController@index');
Route::post('/auth/event-group', 'Auth\EventGroupController@index');

Route::get('/', 'EventController@index');
Route::post('/create/new', 'EventController@create');
Route::get('/confirm', 'EventController@confirm');
Route::post('/confirm/register/{event}', 'EventController@register_confirm');
Route::get('/amounts/add/{event}', 'AmountController@add');
Route::post('/amounts/add/{event}', 'AmountController@store');
Route::get('/amounts/show/{event}', 'AmountController@show');
Route::get('/participants/{event}', 'ParticipantController@index');
Route::get('/setting/{event}', 'SettingController@index');
Route::put('/participant/info/update/{line_friend}', 'ParticipantController@update');
Route::put('/ratio/update/{event}', 'RatioController@update');
Route::delete('/amount/delete/{amount}', 'AmountController@delete');
Route::put('/amount/archive/{amount}', 'AmountController@archive');
Route::put('/amount/unarchive/{amount}', 'AmountController@unarchive');
Route::get('/edit/{event}', 'EventController@editpage');
Route::delete('/delete/{event}', 'EventController@delete');
Route::put('/event/edit/{event}', 'EventController@update');
Route::put('/amount/edit/{amount}', 'AmountController@update');
Route::post('/transaction/add/{event}', 'TransactionController@create');
Route::get('/request/{transaction}', 'TransactionController@request');
Route::put('/sent/{transaction}', 'TransactionController@changeTransactionToSent');
Route::get('/payment/{transaction}', 'TransactionController@payment');
Route::put('/approve/{transaction}', 'TransactionController@changeTransactionToApproved');
Route::delete('/transaction/delete/{transaction}', 'TransactionController@delete');
Route::put('/linefriend/check-not-show/update/{line_friend}', 'LineFriendCotnroller@checkNotShowInfo');
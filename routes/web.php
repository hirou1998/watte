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
});
Route::get('/err/forbidden', function(){
    abort(403, 'Forbidden');
});

Route::get('/', 'EventController@index');
Route::post('/create/new/', 'EventController@create');
Route::get('/confirm', 'EventController@confirm');
Route::post('/confirm/register/{event}', 'EventController@register_confirm');
Route::get('/amounts/add/{event}', 'AmountController@add');
Route::post('/amounts/add/{event}', 'AmountController@store');
Route::get('/amounts/show/{event}', 'AmountController@show');
Route::get('/participants/{event}', 'ParticipantController@index');
Route::get('/setting/{event}', 'SettingController@index');
Route::put('/participant/info/update/{line_friend}', 'ParticipantController@update');
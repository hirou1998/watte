<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/participants/{event}', 'Api\ParitipantController@index')->name('api.participants');
Route::post('/line/callback', 'Api\LineBotController@callback')->name('line.callback');
Route::get('/amount/lists/{event}', 'Api\AmountController@lists')->name('api.amounts');
Route::get('/event/{event}', 'Api\EventController@index')->name('api.event');
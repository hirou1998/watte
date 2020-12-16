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

Route::get('/', 'EventController@index');
Route::post('/create/new/', 'EventController@create');
Route::get('/confirm', 'EventController@confirm');
Route::post('/confirm/register/{event}', 'EventController@register_confirm');
Route::get('/amounts/add/{event}', 'AmountController@index');
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', 'Web\TestController@index');

Route::group(['domain' => 'two.fake'], function ($domain) {
	Route::get('/newly.do', 'Web\TwoFakeController@index');
});
Route::group(['domain' => 'one.fake'], function ($domain) {
	Route::get('/v1', 'Web\OneFakeController@index');
});
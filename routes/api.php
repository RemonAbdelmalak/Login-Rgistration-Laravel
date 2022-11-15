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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'customer'], function () {
    Route::post('signup', 'CustomerController@create');
    Route::post('login', 'CustomerController@login');
    Route::post('update', 'CustomerController@update')->middleware('auth:web');
    Route::post('delete', 'CustomerController@delete')->middleware('auth:web');
});
Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'appo'], function () {
    Route::get('show', 'AppoController@index');
});
Route::group(['middleware' => 'auth:web','namespace' => 'App\Http\Controllers'], function () {
    Route::post('book', 'AppoDetailController@index');
});
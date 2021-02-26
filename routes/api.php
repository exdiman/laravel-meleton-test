<?php

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
Route::group(['namespace' => 'Api'], static function () {
    Route::group(['namespace' => 'v1', 'prefix' => 'v1'], static function () {
        Route::get('/rates', 'RatesController');
        Route::post('/convert', 'ConvertController');
    });
});


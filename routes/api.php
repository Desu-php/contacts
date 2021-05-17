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

Route::middleware('jwt.verify')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'Api\Auth\AuthController@login')->name('login');
    Route::post('/checkCode', 'Api\Auth\AuthController@checkCode');
    Route::post('/register', 'Api\Auth\AuthController@register');

    Route::group(['middleware' => ['jwt.verify'],], function (){
        Route::post('/logout', 'Api\Auth\AuthController@logout');
        Route::post('/refresh', 'Api\Auth\AuthController@refresh');
        Route::get('/user-profile', 'Api\Auth\AuthController@userProfile');
    });

});

Route::group(['middleware' => ['jwt.verify'],], function (){
    Route::get('persons', 'Api\PersonController@index');
});

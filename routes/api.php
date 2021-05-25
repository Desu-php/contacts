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
    Route::get('get_list_persons ', 'Api\PersonController@get_list_persons');
    Route::post('person/upload ', 'Api\PersonController@uploadImage');

    Route::get('get_list_towns', 'Api\CityController@get_list_towns');
    Route::get('get_list_city', 'Api\CityController@get_list_city');

    Route::get('get_list_activity', 'Api\ActivityController@get_list_activity');
    Route::get('get_list_activities', 'Api\ActivityController@get_list_activities');

    Route::get('get_list_company', 'Api\CompanyController@get_list_company');

    Route::get('get_list_info', 'Api\InfoController@get_list_info');


    Route::get('get_list_multiple_id', 'Api\ContactController@get_list_multiple_id');
    Route::get('get_list_contacts', 'Api\ContactController@get_list_contacts');

    Route::get('get_list_note ', 'Api\NoteController@get_list_note');

    Route::get('get_list_image ', 'Api\FileController@get_list_image');


    Route::get('get_list_tags ', 'Api\TagController@get_list_tags');

    Route::post('put_log_change_param', 'Api\LogActivityController@put_log_change_param');
    Route::get('get_log_change_param', 'Api\LogActivityController@get_log_change_param');
});

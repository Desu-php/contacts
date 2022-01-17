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


Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'Api\Auth\AuthController@login')->name('api.login');
    Route::post('/checkCode', 'Api\Auth\AuthController@checkCode');
    Route::post('/register', 'Api\Auth\AuthController@register');
    Route::post('/refresh', 'Api\Auth\AuthController@refresh');

    Route::group(['middleware' => ['auth:api'],], function (){
        Route::post('/logout', 'Api\Auth\AuthController@logout');
        Route::get('/user-profile', 'Api\Auth\AuthController@userProfile');
    });

});

Route::group(['namespace' => 'Api','middleware' => ['auth:api'],], function (){

    Route::group(['prefix' => 'persons'], function (){
        Route::get('dismantled/count', 'PersonController@getDismantledPersonsCount');
    });

    Route::get('get_list_persons', 'PersonController@get_list_persons');
    Route::post('person/upload', 'PersonController@upload');

    Route::get('get_user_info', 'PersonController@get_user_info');

    Route::get('get_list_towns', 'CityController@get_list_towns');
    Route::get(' ', 'CityController@get_list_city');

    Route::get('get_list_activity', 'ActivityController@get_list_activity');
    Route::get('get_list_activities', 'ActivityController@get_list_activities');

    Route::get('get_list_company', 'CompanyController@get_list_company');

    Route::get('get_list_info', 'InfoController@get_list_info');


    Route::get('get_list_multiple_id', 'ContactController@get_list_multiple_id');
    Route::get('get_list_contacts', 'ContactController@get_list_contacts');

    Route::get('get_list_note ', 'NoteController@get_list_note');

    Route::get('get_list_image ', 'FileController@get_list_image');


    Route::get('get_list_tags ', 'TagController@get_list_tags');

    Route::post('put_log_change_param', 'LogActivityController@put_log_change_param');
    Route::get('get_log_change_param', 'LogActivityController@get_log_change_param');

    Route::get('sharing/{id}', 'SharingController@show')->name('sharing.show');
    Route::post('sharing', 'SharingController@store')->name('sharing.store');
    Route::put('sharing/{id}', 'SharingController@update')->name('sharing.update');
    Route::delete('sharing/{id}', 'SharingController@destroy');
    Route::delete('sharing_user_access_off', 'SharingController@sharing_user_access_off');
    Route::get('get_sharings_list', 'SharingController@get_sharings_list');
    Route::get('get_sharing_access_users/{sharing_id}', 'SharingController@get_sharing_access_users');
    Route::get('sharing_search', 'SharingController@search');
    Route::delete('sharing/unsubscribe/{id}', 'SharingController@unsubscribe');
    Route::get('get_sharing_persons/{id}', 'SharingController@get_sharing_persons');
    Route::get('get_my_sharing_list', 'SharingController@get_my_sharing_list');
    Route::get('get_sharing/{id}', 'SharingController@getSharing');

    Route::group(['prefix' => 'countries'], function (){
        Route::get('/', 'CountryController@getCountries');
    });

    Route::group(['prefix' => 'user'], function (){
        Route::delete('/', 'UserController@destroy');
        Route::post('change_user_info', 'UserController@change_user_info');
        Route::get('me', 'UserController@me');
    });

    Route::group(['prefix' => 'scores'], function (){
        Route::post('/', 'ScoreController@store');
        Route::get('/', 'ScoreController@getScores');
        Route::get('indicator', 'ScoreController@getIndicator');
        Route::get('last', 'ScoreController@last');
        Route::get('statistics', 'ScoreController@statistics');
    });

    Route::group(['prefix' => 'tasks'], function (){
       Route::get('/', 'TaskController@getTasks');
       Route::post('/', 'TaskController@store');
       Route::delete('/{task}', 'TaskController@destroy');
       Route::put('/{task}', 'TaskController@update');
       Route::get('/statuses', 'TaskController@getStatuses');
    });
//    Route::delete('sharing_users_access_off', 'SharingController@sharing_users_access_off');
});

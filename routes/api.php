<?php
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

// TODO: Need to protect routes with an API key which identifies the User
// accessing the route... except maybe the route for registering ...
// TODO: Replace resources with RESTful endpoints, we'll change them back 
// to resources when we know what we're doing
// TODO: Merge topic and topic thread controllers, there's really no
// need to separate those out when we have such a succint api
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    // Sections
    // Route::apiResource('sections', 'SectionController')->middleware('auth:api');
    Route::post('/sections', 'SectionController@store')->middleware('auth:api');
    Route::get('/sections', 'SectionController@index');
    Route::get('/sections/{section}', 'SectionController@show');

    // Messages
    // Route::apiResource('/topic/{topic}/messages', 'MessageController')->middleware('auth:api');
    Route::post('/messages/{message}/reply', 'MessageController@reply')->middleware('auth:api');
    Route::post('/messages/{message}', 'MessageController@update')->middleware('auth:api');

    // Topics
    Route::post('/topics/{topic}/messages', 'TopicController@postMessage')->middleware('auth:api');
    Route::get('/topics/{topic}/messages', 'TopicController@messages');
    Route::post('/sections/{section}/topics', 'TopicController@store')->middleware('auth:api');
    Route::get('/sections/{section}/topics', 'TopicController@index');
    //Route::apiResource('/sections/{section}/topics', 'TopicController')->middleware('auth:api');


    // Users
    Route::get('/users/{user}/profile', 'UserProfileController@show')->middleware('auth:api');
    Route::patch('/users/self/profile', 'UserProfileController@update')->middleware('auth:api');
    // TODO: Seems unsafe to expose users as a direct storage so we'll comment it out until we're sure we need it
    //Route::apiResource('users', 'UserController');
});

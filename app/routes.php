<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@getIndex'));
Route::get('/api/v2/uuid/{name}', array('as' => 'uuid', 'uses' => 'HomeController@getUuid'));
Route::get('/api/v2/name/{uuid}', array('as' => 'name', 'uses' => 'HomeController@getName'));
Route::get('/api/v2/uuid/list/{names}', array('as' => 'uuidList', 'uses' => 'HomeController@getUuidList'));
Route::get('/api/v2/name/list/{uuids}', array('as' => 'nameList', 'uses' => 'HomeController@getNameList'));
Route::get('/api/v2/history/{player}', array('as' => 'history', 'uses' => 'HomeController@getHistory'));
Route::get('/api/v2/random', array('as' => 'random', 'uses' => 'HomeController@getRandom'));
Route::get('/api/v2/random/{amount}', array('as' => 'randomAmount', 'uses' => 'HomeController@getRandom'));

Route::post('/api/v2/uuid', array('as' => 'uuidPost', 'uses' => 'HomeController@postUuid'));
Route::post('/api/v2/name', array('as' => 'namePost', 'uses' => 'HomeController@postName'));
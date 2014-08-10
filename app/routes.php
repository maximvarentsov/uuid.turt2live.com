<?php

// === BASE ===

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@getIndex'));
Route::get('/v1', array('as' => 'v1', 'uses' => 'HomeController@getIndexV1'));

// === VERSION 1 ===

Route::get('/uuid/{name}', array('as' => 'uuidv1', 'uses' => 'ApiV1Controller@getUuid'));
Route::get('/name/{uuid}', array('as' => 'namev1', 'uses' => 'ApiV1Controller@getName'));
Route::get('/uuid/list/{names}', array('as' => 'uuidListv1', 'uses' => 'ApiV1Controller@getUuidList'));
Route::get('/name/list/{uuids}', array('as' => 'nameListv1', 'uses' => 'ApiV1Controller@getNameList'));
Route::get('/history/{uuid}', array('as' => 'historyv1', 'uses' => 'ApiV1Controller@getHistory'));
Route::get('/random', array('as' => 'randomv1', 'uses' => 'ApiV1Controller@getRandom'));
Route::get('/random/{amount}', array('as' => 'randomAmountv1', 'uses' => 'ApiV1Controller@getRandom'));

Route::post('/uuid', array('as' => 'uuidPostv1', 'uses' => 'ApiV1Controller@postUuid'));
Route::post('/name', array('as' => 'namePostv1', 'uses' => 'ApiV1Controller@postName'));

// Version 1 alternatives

Route::get('/api/v1/uuid/{name}', array('as' => 'uuidAltv1', 'uses' => 'ApiV1Controller@getUuid'));
Route::get('/api/v1/name/{uuid}', array('as' => 'nameAltv1', 'uses' => 'ApiV1Controller@getName'));
Route::get('/api/v1/uuid/list/{names}', array('as' => 'uuidListAltv1', 'uses' => 'ApiV1Controller@getUuidList'));
Route::get('/api/v1/name/list/{uuids}', array('as' => 'nameListAltv1', 'uses' => 'ApiV1Controller@getNameList'));
Route::get('/api/v1/history/{uuid}', array('as' => 'historyAltv1', 'uses' => 'ApiV1Controller@getHistory'));
Route::get('/api/v1/random', array('as' => 'randomAltv1', 'uses' => 'ApiV1Controller@getRandom'));
Route::get('/api/v1/random/{amount}', array('as' => 'randomAmountAltv1', 'uses' => 'ApiV1Controller@getRandom'));

Route::post('/api/v1/uuid', array('as' => 'uuidPostAltv1', 'uses' => 'ApiV1Controller@postUuid'));
Route::post('/api/v1/name', array('as' => 'namePostAltv1', 'uses' => 'ApiV1Controller@postName'));

// === VERSION 2 ===

Route::get('/api/v2/uuid/{name}', array('as' => 'uuid', 'uses' => 'ApiV2Controller@getUuid'));
Route::get('/api/v2/uuid/{name}/offline', array('as' => 'uuidOffline', 'uses' => 'ApiV2Controller@getUuidOffline'));
Route::get('/api/v2/name/{uuid}', array('as' => 'name', 'uses' => 'ApiV2Controller@getName'));
Route::get('/api/v2/uuid/list/{names}', array('as' => 'uuidList', 'uses' => 'ApiV2Controller@getUuidList'));
Route::get('/api/v2/name/list/{uuids}', array('as' => 'nameList', 'uses' => 'ApiV2Controller@getNameList'));
Route::get('/api/v2/history/{player}', array('as' => 'history', 'uses' => 'ApiV2Controller@getHistory'));
Route::get('/api/v2/random', array('as' => 'random', 'uses' => 'ApiV2Controller@getRandom'));
Route::get('/api/v2/random/{amount}', array('as' => 'randomAmount', 'uses' => 'ApiV2Controller@getRandom'));
Route::get('/api/v2/info', array('as' => 'info', 'uses' => 'ApiV2Controller@getInfo'));

Route::get('/api/v2/errortest', array('as' => 'testError', 'uses' => 'ApiV2Controller@getErrorTest'));
Route::get('/api/v2/dashes/{uuid}', array('as' => 'dashes', 'uses' => 'ApiV2Controller@getInsertDashes'));
Route::get('/api/v2/nodashes/{uuid}', array('as' => 'nodashes', 'uses' => 'ApiV2Controller@getStripDashes'));

Route::post('/api/v2/uuid', array('as' => 'uuidPost', 'uses' => 'ApiV2Controller@postUuid'));
Route::post('/api/v2/name', array('as' => 'namePost', 'uses' => 'ApiV2Controller@postName'));
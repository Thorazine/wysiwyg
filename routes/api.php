<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('media/upload', 'Api\MediaController@upload')->name('api.media.upload');
Route::get('media', 'Api\MediaController@index')->name('api.media.index');
Route::get('media/edit', 'Api\MediaController@edit')->name('api.media.edit');
Route::post('media/update', 'Api\MediaController@update')->name('api.media.update');

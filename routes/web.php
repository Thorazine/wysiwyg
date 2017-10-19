<?php

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

Route::get('/', 'WysiwygController@index')->name('wysiwyg.index');
Route::post('/image', 'WysiwygController@image')->name('wysiwyg.image');

Route::get('/test', function() {
	$wysiwyg = new App\Classes\WysiwygBuilder;

	$content = 'Edit the Expression & Text to see matches.<img src="http://something.nl" class="media-id-1232456">
	Roll over matches or the expression for details.<img src="http://something.nl" class="media-id-1">Undo mistakes
	with cmd-z. Save Favorites & Share expressions with friends or the Community.
	Explore your results with Tools. A full Reference & Help is available in the Library,
	or watch the video Tutorial.';

	$wysiwyg->saveReplace($content);
});

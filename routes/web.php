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
Route::post('/form/store', 'FormController@store')->name('form.store');

Route::get('/test', function() {
	$wysiwyg = new App\Classes\WysiwygBuilder;

	$content = 'Edit the Expression & Text to see matches.<img class="left media-id-1657" src="someurl" width="113" height="97" />
	Roll over matches or the expression for details.<img class="left media-id-1" src="blob:http://localhost/e443eeb3-46e0-4f0a-aeb5-95d3d897d1ed" width="113" height="97" />Undo mistakes
	with cmd-z. Save Favorites & Share expressions with friends or the Community.
	Explore your results with Tools. A full Reference & Help is available in the Library,
	or watch the video Tutorial.';

	$wysiwyg->saveReplace($content);
});

// Localization
Route::get('/js/lang.js', function () {
    // $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }

    //     return $strings;
    // });

    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');

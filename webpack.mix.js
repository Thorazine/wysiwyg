let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
	.js('resources/assets/js/library.js', 'public/js')
   	.scripts([
   		'node_modules/jquery/dist/jquery.min.js',
   		'resources/assets/js/plugins/library/plugin.js',
   	], 'public/js/bundle.js')

   	.sass('resources/assets/sass/app.scss', 'public/css');

var LiveReloadPlugin = require('webpack-livereload-plugin');

mix.webpackConfig({
    plugins: [
        new LiveReloadPlugin()
    ]
});

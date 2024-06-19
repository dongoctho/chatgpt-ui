const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.version()

    // Add js here
    .js('resources/js/app.js', 'public/build/js').vue()

    // Add css here
    .sass('resources/scss/app.scss', 'public/build/css')

    .sourceMaps();

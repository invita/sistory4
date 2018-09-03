const { mix } = require('laravel-mix');

mix.disableNotifications();


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

//mix.js('resources/assets/js/app.js', 'public/js')
//   .sass('resources/assets/sass/app.scss', 'public/css');

mix.sass('resources/assets/sass/common-fe.scss', 'public/css');
mix.sass('resources/assets/sass/sites/sidih.scss', 'public/sites/sidih/css/fe.css');
mix.sass('resources/assets/sass/sites/sistory.scss', 'public/sites/sistory/css/fe.css');

mix.combine([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/jquery-ui-bundle/jquery-ui.min.js',
    'node_modules/foundation-sites/dist/js/foundation.min.js',
    'resources/assets/js/common/jsData.js',
    'resources/assets/js/common/accordion.js',
    'resources/assets/js/common/topMenu.js',
    'resources/assets/js/search/autocomplete.js',
    'resources/assets/js/search/advancedSearch.js',
    'resources/assets/js/search/matchHighlighter.js',
    'resources/assets/js/app.js',
], 'public/js/app.js');


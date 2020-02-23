const mix = require('laravel-mix');

// Extend Mix with the "i18n" method, that loads the vue-i18n-loader
mix.extend('i18n', new class {
    webpackRules() {
        return [{
            resourceQuery: /blockType=i18n/,
            type: 'javascript/auto',
            loader: '@kazupon/vue-i18n-loader',
        }, ];
    }
}(), );

let hosts = process.env.APP_URL.split('//');
mix.options({
    hmrOptions: {
        host: hosts[1],
        port: '8080'
    }
});

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

// Call the .i18n() (to load the loader) before .js(..., ...)

mix.i18n()
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').sourceMaps();

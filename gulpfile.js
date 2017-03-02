var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.css.autoprefix.options.browsers = ['> 1%', 'Last 10 versions', 'IE 8'];

require('laravel-elixir-webpack-official');

require('laravel-elixir-vue-2');

elixir(function(mix) {
    mix.webpack('app.js', 'public/assets/portal/js/app.js');
    mix.webpack('bundle.js', 'public/assets/portal/js/bundle.js');
    mix.webpack('casino.js', 'public/assets/portal/js/casino.js');

    mix.sass('sports.scss', 'public/assets/portal/css/sports.css');
    mix.sass('casino.scss', 'public/assets/portal/css/casino.css');
    mix.sass('app.scss', 'public/assets/portal/css/app.css');
    mix.less('style.less', 'public/assets/portal/css/style.css');
});
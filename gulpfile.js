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

elixir(function(mix) {
    mix.webpack('app.js', 'public/assets/portal/js/app.js');

    mix.less('style.less', 'public/assets/portal/newstyle/style.css');

    mix.sass('sports.scss', 'public/assets/portal/css/sports.css');
    mix.sass('app.scss', 'public/assets/portal/css/app.css');
    mix.less('style.less', 'public/assets/portal/newStyle/style.css');
});
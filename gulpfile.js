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

elixir(function(mix) {
    mix.webpack('app.js', 'public/assets/portal/js/app.js');

    mix.sass('sports.scss', 'public/assets/portal/css/sports.css');

    // mix.sass('sports/betslip.scss', 'public/assets/portal/css/bet-details.css');

    mix.sass('page-footer/page-footer.scss', 'public/assets/portal/css/portal.css');
});
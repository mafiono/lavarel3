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
    mix.scripts([
        'sports/sportsPartials.js',
        'sports/marketsPartials.js',
        'sports/betslipPartials.js',
        'sports/sportsMenu.js',
        'sports/markets.js',
        'sports/betslip.js',
        'sports/favorites.js',
        'sports/search.js',
        'sports/updater.js'
    ], 'public/assets/portal/js/sports.js');
    
    mix.sass('sports.scss', 'public/assets/portal/css/sports.css');
});
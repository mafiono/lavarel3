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
        'helpers/helpers.js',

        'routes/routes.js',

        'breadcrumb/breadcrumbPartials.js',
        'breadcrumb/breadcrumb.js',

        'info/infoPartials.js',
        'info/info.js',

        'statistics/statistics.js',
        'statistics/statisticsPartials.js',

        'sportsMenu/sportsMenu.js',
        'sportsMenu/sportsMenuPartials.js',
        'sportsMenu/regionsMenu.js',
        'sportsMenu/regionsMenuPartials.js',
        'sportsMenu/competitionsMenu.js',
        'sportsMenu/competitionsMenuPartials.js',
        'sportsMenu/fixturesMenu.js',
        'sportsMenu/fixturesMenuPartials.js',

        'sports/matchStatePartials.js',
        'sports/leftMenuPartials.js',
        'sports/fixturesPartials.js',
        'sports/marketsPartials.js',
        'sports/betslipPartials.js',
        // 'sports/liveMenuPartials.js',
        'sports/leftMenu.js',
        // 'sports/liveMenu.js',
        'sports/fixtures.js',
        'sports/markets.js',
        'sports/betslip.js',
        'sports/favorites.js',
        'sports/search.js',

        'updaters/fixturesMenuUpdater.js',
        'updaters/selectionsUpdater.js',

        'terminalVerifier/terminalVerifierPartials.js',
        'terminalVerifier/terminalVerifier.js',

        'globalSettings/globalSettings.js'

    ], 'public/assets/portal/js/sports.js');
    
    mix.sass('sports.scss', 'public/assets/portal/css/sports.css');

    // mix.sass('sports/betslip.scss', 'public/assets/portal/css/bet-details.css');

    mix.sass('page-footer/page-footer.scss', 'public/assets/portal/css/portal.css');
});
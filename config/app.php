<?php

return [

    /*
      |--------------------------------------------------------------------------
      | Application Debug Mode
      |--------------------------------------------------------------------------
      |
      | When your application is in debug mode, detailed error messages with
      | stack traces will be shown on every error that occurs within your
      | application. If disabled, a simple generic error page is shown.
      |
     */

    'debug' => env('APP_DEBUG', false),
    /*
      |--------------------------------------------------------------------------
      | Application URL
      |--------------------------------------------------------------------------
      |
      | This URL is used by the console to properly generate URLs when using
      | the Artisan command line tool. You should set this to the root of
      | your application so that it is used when running Artisan tasks.
      |
     */
    'url' => 'http://localhost',
    /*
      |--------------------------------------------------------------------------
      | Application Timezone
      |--------------------------------------------------------------------------
      |
      | Here you may specify the default timezone for your application, which
      | will be used by the PHP date and date-time functions. We have gone
      | ahead and set this to a sensible default for you out of the box.
      |
     */
    'timezone' => 'Europe/Lisbon',
    /*
      |--------------------------------------------------------------------------
      | Application Locale Configuration
      |--------------------------------------------------------------------------
      |
      | The application locale determines the default locale that will be used
      | by the translation service provider. You are free to set this value
      | to any of the locales which will be supported by the application.
      |
     */
    'locale' => 'en',
    /*
      |--------------------------------------------------------------------------
      | Application Fallback Locale
      |--------------------------------------------------------------------------
      |
      | The fallback locale determines the locale to use when the current one
      | is not available. You may change the value to correspond to any of
      | the language folders that are provided through your application.
      |
     */
    'fallback_locale' => 'pt',
    /*
      |--------------------------------------------------------------------------
      | Encryption Key
      |--------------------------------------------------------------------------
      |
      | This key is used by the Illuminate encrypter service and should be set
      | to a random, 32 character string, otherwise these encrypted strings
      | will not be safe. Please do this before deploying an application!
      |
     */
    'key' => env('APP_KEY', 'tjjvW1aWv7JPhYtOpsO8xeRrFXo1vlSR'),
    'cipher' => 'AES-256-CBC',
    /*
      |--------------------------------------------------------------------------
      | Logging Configuration
      |--------------------------------------------------------------------------
      |
      | Here you may configure the log settings for your application. Out of
      | the box, Laravel uses the Monolog PHP logging library. This gives
      | you a variety of powerful log handlers / formatters to utilize.
      |
      | Available Settings: "single", "daily", "syslog", "errorlog"
      |
     */
    'log' => 'single',
    /*
      |--------------------------------------------------------------------------
      | Autoloaded Service Providers
      |--------------------------------------------------------------------------
      |
      | The service providers listed here will be automatically loaded on the
      | request to your application. Feel free to add your own services to
      | this array to grant expanded functionality to your applications.
      |
     */
    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Routing\ControllerServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        App\Providers\DbSessionServiceProvider::class,
//        Rairlie\LockingSession\LockingSessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\SportsBonusServiceProvider::class,
        App\Providers\CasinoBonusServiceProvider::class,

        Barryvdh\Debugbar\ServiceProvider::class,
        'Anchu\Ftp\FtpServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
        'Chencha\Share\ShareServiceProvider',
        'Nathanmac\Utilities\Parser\ParserServiceProvider',
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        'Tymon\JWTAuth\Providers\JWTAuthServiceProvider',
        'GrahamCampbell\Exceptions\ExceptionsServiceProvider',
    ],
    /*
      |--------------------------------------------------------------------------
      | Class Aliases
      |--------------------------------------------------------------------------
      |
      | This array of class aliases will be registered when this application
      | is started. However, feel free to register as many as you wish as
      | the aliases are "lazy" loaded so they don't hinder performance.
      |
     */
    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Input' => Illuminate\Support\Facades\Input::class,
        'Inspiring' => Illuminate\Foundation\Inspiring::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'SportsBonus' => App\Bonus\Sports\SportsBonusFacade::class,
        'CasinoBonus' => App\Bonus\Casino\CasinoBonusFacade::class,
        'Helper' => App\Helpers\Helper::class,
        'HTML' => 'Illuminate\Html\HtmlFacade',
        'Form' => 'Illuminate\Html\FormFacade',
        'Share' => 'Chencha\Share\ShareFacade',
        'Parser' => 'Nathanmac\Utilities\Parser\Facades\Parser',
        'JWTAuth' => 'Tymon\JWTAuth\Facades\JWTAuth',
        'JWTFactory' => 'Tymon\JWTAuth\Facades\JWTFactory',
    ],

    'rand_hash' => substr(md5(microtime()),random_int(0,26),5),
    'odds_server' => env('ODDS_SERVER'),

    'odds_db' => env('DB_ODDS_DATABASE', 'betgenius'),
    'casino_db' => env('DB_CASINO_DATABASE', 'betcasino'),

    'casino_lobby' => env('CASINO_LOBBY'),
    'isoftbet_launcher' => env('ISOFTBET_LAUNCHER'),
    'casino_available' => env('CASINO_AVAILABLE', 0),

    'srij_ws_active' => env('SRIJ_WS_ACTIVE'),
    'srij_company_code' => env('SRIJ_COMPANY_CODE'),
    'srij_self_exclusion' => env('SRIJ_SELF_EXCLUSION'),
    'srij_identity' => env('SRIJ_IDENTITY'),

    'bet_submit_delay' => env('BET_SUBMIT_DELAY', 10)*1,

    'block_user_time' => env('BLOCK_USER_TIME', 10),

    'promotions_images_path' => env('PROMOTIONS_IMAGES_PATH'),
    'ads_images_path' => env('ADS_IMAGES_PATH'),

    'server_url' => env('SERVER_URL', 'https://www.casinoportugal.pt/'),

    'netent_wsdl' => env('NETENT_WSDL'),
    'netent_merchant_id' => env('NETENT_MERCHANT_ID'),
    'netent_merchant_password' => env('NETENT_MERCHANT_PASSWORD'),
    'netent_static_server' => env('NETENT_STATIC_SERVER'),
    'netent_game_server' => env('NETENT_GAME_SERVER'),

    'address_required' => env('ADDRESS_REQUIRED', 0),
];

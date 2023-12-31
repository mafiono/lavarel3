<?php
$is_auth = $_COOKIE['is_auth'] ?? '';
$path = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
if (0 === strpos($path, '//')) {
    ob_start();
    header('Location: /');
    ob_end_flush();
    die();
}

if ($_SERVER['HTTP_HOST'] === 'casinoportugal.pt'
    || $_SERVER['HTTP_HOST'] === 'www.casinoportugal.pt'
    || 0 === strpos($path, '/ws')
    || 0 === strpos($path, '/api')
    || 0 === strpos($path, '/bem-vindo')
    || 0 === strpos($path, '/perfil/banco/depositar')
) {
    // ignore this pages for auth
} else if (empty($is_auth) || $is_auth !== 'authorized') {
    ob_start();
    header('Location: /auth.php');
    ob_end_flush();
    die();
}
// Redirect casinoportugal.pt to www.casinoportugal.pt
if ($_SERVER['HTTP_HOST'] === 'casinoportugal.pt') {
    header('Location: https://www.casinoportugal.pt'.$_SERVER['REQUEST_URI']);
    exit(0);
}
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


register_shutdown_function( "fatal_handler" );

function fatal_handler()
{
    $errfile = "unknown file";
    $errstr = "shutdown";
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

        if (ob_get_length()) { ob_clean(); }
        if (function_exists('dd') && env('APP_DEBUG', false)) {
            dd($errno, $errfile, $errline, $errstr);
        }

        error_log("$errno: Text: $errstr:: \n File: $errfile :: $errline");
        include __DIR__ . '/../resources/views/errors/500.fatal.php';
        die();
    }
}

set_error_handler('handlePhpErrors');
function handlePhpErrors($errno, $errmsg, $filename, $linenum, $vars) {
    if (stristr($errmsg, "SoapClient::SoapClient")) {
        error_log($errmsg); // silently log error
        return; // skip error handling
    }
}

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/
require __DIR__.'/../bootstrap/autoload.php';

require_once __DIR__.'/verificaIp.php';

require_once __DIR__.'/allowed_domains.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

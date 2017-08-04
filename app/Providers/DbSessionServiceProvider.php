<?php

namespace App\Providers;

use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Session\EncryptedStore;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DbSessionServiceProvider extends ServiceProvider {
    protected $logger;
    public function register()
    {
        $this->logger = new Logger('sessions');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/sessions.log'), Logger::DEBUG));

        $connection = $this->app['config']['session.connection'];
        $table = $this->app['config']['session.table'];
        $minutes = $this->app['config']['session.lifetime'];

        $this->app->singleton('session', function ($app) {
            return new MySessionManager($app);
        });
        $this->app['session']->extend('database', function($app) use ($connection, $table, $minutes){
            $db = new CustomDatabaseSessionHandler(
                $this->app['db']->connection($connection),
                $table,
                $minutes
            );
            $db->setLogger($this->logger);
            return $db;
        });
    }
}
class MySessionManager extends SessionManager {
    protected function buildSession($handler)
    {
        if ($this->app['config']['session.encrypt']) {
            return new EncryptedStore(
                $this->app['config']['session.cookie'], $handler, $this->app['encrypter']
            );
        } else {
            $name = $this->app['config']['session.cookie'];
            return new Store($name, $handler, request()->cookie($name));
        }
    }
}
class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    /** @var Logger $logger */
    protected $logger;
    public function destroy($sessionId)
    {
        parent::destroy($sessionId);
        $this->setExists(false);
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
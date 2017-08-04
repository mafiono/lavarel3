<?php

namespace App\Providers;

use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Session\EncryptedStore;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\ServiceProvider;

class DbSessionServiceProvider extends ServiceProvider {
    protected $logger;
    public function register()
    {
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
            return $db;
        });
    }
}
class MySessionManager extends SessionManager {
    protected function buildSession($handler)
    {
        $name = $this->app['config']['session.cookie'];
        $id = request()->cookie($name);
        if ($this->app['config']['session.encrypt']) {
            return new EncryptedStore($name, $handler, $this->app['encrypter'], $id);
        }
        return new Store($name, $handler, $id);
    }
}
class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    public function destroy($sessionId)
    {
        parent::destroy($sessionId);
        $this->setExists(false);
    }
}
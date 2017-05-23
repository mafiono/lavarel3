<?php

namespace App\Providers;

use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\ServiceProvider;

class DbSessionServiceProvider extends ServiceProvider {

    public function register()
    {
        $connection = $this->app['config']['session.connection'];
        $table = $this->app['config']['session.table'];
        $minutes = $this->app['config']['session.lifetime'];

        $this->app['session']->extend('database', function($app) use ($connection, $table, $minutes){
            return new CustomDatabaseSessionHandler(
                $this->app['db']->connection($connection),
                $table,
                $minutes
            );
        });
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
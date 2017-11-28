<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\ConnectionInterface;
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
                $minutes,
                $this->app
            );
            return $db;
        });
    }
}
class MySessionManager extends SessionManager {
    protected function buildSession($handler)
    {
        $name = $this->app['config']['session.cookie'];
        $id = request()->cookie($name, '');
        if ($this->app['config']['session.encrypt']) {
            return new EncryptedStore($name, $handler, $this->app['encrypter'], $id);
        }
        return new Store($name, $handler, $id);
    }
}
class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Create a new database session handler instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  string  $table
     * @param  string  $minutes
     * @param  \Illuminate\Contracts\Container\Container|null  $container
     * @return void
     */
    public function __construct(ConnectionInterface $connection, $table, $minutes, Container $container = null)
    {
        $this->container = $container;
        parent::__construct($connection, $table, $minutes);
    }

    public function destroy($sessionId)
    {
        parent::destroy($sessionId);
        $this->setExists(false);
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $payload = $this->getDefaultPayload($data);

        if (! $this->exists) {
            $this->read($sessionId);
        }

        if ($this->exists) {
            $this->getQuery()->where('id', $sessionId)->update($payload);
        } else {
            $payload['id'] = $sessionId;

            $this->getQuery()->insert($payload);
        }

        $this->exists = true;
    }

    /**
     * Get the default payload for the session.
     *
     * @param  string  $data
     * @return array
     */
    protected function getDefaultPayload($data)
    {
        $payload = ['payload' => base64_encode($data), 'last_activity' => time()];

        if (! $container = $this->container) {
            return $payload;
        }

        if ($container->bound(Guard::class)) {
            $payload['user_id'] = $container->make(Guard::class)->id();
        }

        if ($container->bound('request')) {
            $payload['ip_address'] = $container->make('request')->ip();

            $payload['user_agent'] = substr(
                (string) $container->make('request')->header('User-Agent'), 0, 500
            );
        }

        return $payload;
    }
}
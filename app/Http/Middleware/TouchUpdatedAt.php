<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;

class TouchUpdatedAt
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure|\Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('FORCE_PROTOCOL', false)){
            // sett protocol to use always https
            Redirect::getUrlGenerator()->forceSchema('https');
        }
        if (env('APP_ENV') === 'testing') {
            return $next($request);
        }
        /* @var User $user */
        if (!$this->auth->guest()) {
            $user = $this->auth->user();
            if ($user !== null) {
                if (!ends_with($user->getLastSession()->session_id, \Session::getId())){
                    $this->auth->logout();
                    \Session::flush();
                } else {
                    if ($user->lastSeenNow()) {
                        $this->auth->logout();
                        \Session::flush();
                    }
                }
            }
        }
        return $next($request);
    }
}

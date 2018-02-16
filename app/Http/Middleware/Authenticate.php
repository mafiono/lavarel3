<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Response;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $except = [
        'api/selections/*',
        'api/active',
        'api/*/values',
        'api/lastactive',
        'api/categories/*',
    ];
    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!$this->shouldPassThrough($request)) {
                if ($this->auth->guest()) {
                    if ($request->ajax()) {
                        return Response::json(['msg' => 'Acesso não autorizado', 'status' => 'error', 'type' => 'reload'], 401);
                    }
                    return redirect()->guest('/');
                }
            }
        } catch (\Exception $e) {

        }

        return $next($request);
    }

    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}

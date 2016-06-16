<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;

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
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!$this->auth->guest()) {
                $user = $this->auth->user();
                if ($user !== null) {
                    $user->lastSeenNow();
                }
            }
        } catch (TokenMismatchException $e) {
            return response('Invalid security token.', 401);
        }
    }
}

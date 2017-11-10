<?php

namespace App\Http\Middleware;

use App\User;
use Carbon\Carbon;
use Closure;
use Cookie;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Log;
use Session;

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
        try {
            $data = Cookie::get('my_testing_session') ?? '[]';
            $data = json_decode($data, true);
            if (!$this->auth->guest()) {
                $user = $this->auth->user();
                if ($user !== null) {
                    $id = Session::getId();
                    if (!isset($data[$id])) {
                        $data[$id] = [
                            'user' => $user->id,
                            'recover' => 0,
                            'time_start' => Carbon::now()->format('H:i:s'),
                        ];
                    }
                    $data[$id]['time_last'] = Carbon::now()->format('H:i:s');
                    Cookie::queue('my_testing_session', json_encode($data), 20);
                    if (!ends_with($user->getLastSession()->session_id, Session::getId())) {
                        $this->auth->logout();
                        Session::flush();
                    } else {
                        if ($user->lastSeenNow()) {
                            $this->auth->logout();
                            Session::flush();
                        }
                    }
                }
            } else {
                $currId = Session::getId();
                foreach ($data as $sess_id => $user_id) {
                    Session::setId($sess_id);
                    Session::start();
                    if ($this->auth->check()) {
                        $data[$sess_id]['recover'] += 1;
                        $data[$sess_id]['time_last'] = Carbon::now()->format('H:i:s');
                        Cookie::queue('my_testing_session', json_encode($data), 20);
                        $currId = $sess_id;
                        break;
                    }
                }
                if ($currId !== Session::getId()) {
                    Session::setId($currId);
                    Session::start();
                }
                Cookie::queue('my_testing_session', json_encode($data), 20);
            }
        } catch (\Exception $e) {
            Log::error('Error in updated session', ['error' => $e->getTraceAsString(), 'session' => Session::all()]);
        }
        return $next($request);
    }
}

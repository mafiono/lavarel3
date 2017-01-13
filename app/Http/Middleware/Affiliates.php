<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;



class Affiliates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (isset($_GET['btag'])) {

            Cookie::queue('btag', $_GET['btag'], 45000);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;


class Affiliates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($_GET['btag'])) {
            $btag = $_GET['btag'];
            $btag = preg_match('/^(\d{3,19})(([_\-.])(\d{1,20}))?/', $btag, $matches);
            if ($btag && count($matches) > 1) {
                $btag = $matches[1];
                if (count($matches)>3) {
                    $btag .= '_' . $matches[4];
                }
                Cookie::queue('btag', $btag, 45000);
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;
use Response;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "balance",
        'api/*',
        'portal/comunicacao/*',
        'banco/depositar/meowallet/redirect',
        'api/login',
        'server',
        'registar/*',
        'bc',
        'bc2'
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
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            if ($request->ajax()) {
                return Response::json([
                    'msg' => 'Código de segurança inválido',
                    'status' => 'error',
                    'type' => 'reload',
                    'token' => csrf_token()
                ], 401);
            } else {
                return redirect()->guest('/');
            }
        }
    }
}

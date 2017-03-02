<?php

namespace App\Exceptions;

use App\Models\Error;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
//use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use GrahamCampbell\Exceptions\ExceptionHandler as ExceptionHandler;

//class Handler extends ExceptionHandler
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        $this->logError($e);

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } else if ($e instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } else if ($request->isXmlHttpRequest()) {
            return response()->json(['msg' => $e->getMessage(), 'status' => 'error', 'type' => 'error'],
                $e->getStatusCode());
        }
        return parent::render($request, $e);
    }

    /**
     * @param  \Exception  $e
     */
    private function logError(Exception $e)
    {
        $error = $e->getTraceAsString();
        $msg = $e->getMessage();
        $type = get_class($e);

        Error::create(compact('error', 'msg', 'type'));
    }
}

<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Response, Session;

trait GenericResponseTrait
{
    /**
     * Generic Response based on type of request.
     * @param $type
     * @param $msg
     * @return JsonResponse|RedirectResponse
     */
    private function respType($type, $msg)
    {
        $ajax = $this->request->ajax();
        if ($ajax) {
            return Response::json([
                'status' => $type,
                'type' => $type,
                'msg' => $msg,
            ], $type === 'success' ? 200 : 400);
        }
        Session::flash($type, $msg);
        return back();
    }

    /**
     * Generic Response based on type of request.
     * @param $type
     * @param $msg
     * @return JsonResponse|RedirectResponse
     */
    private function resp($type, $msg)
    {
        $ajax = $this->request->ajax();
        if ($ajax) {
            return Response::json([$type => $msg], $type === 'success' ? 200 : 400);
        }
        Session::flash($type, $msg);
        return back();
    }
}

<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Response, Session;

trait GenericResponseTrait
{
    /**
     * Generic Response based on type of request.
     * @param $status
     * @param $msg
     * @param $type
     * @return JsonResponse|RedirectResponse
     */
    private function respType($status, $msg, $type = null)
    {
        if ($type === null) $type = [
            'type' => $status
        ];
        if (is_string($type)) $type = [
            'type' => $type
        ];
        $ajax = $this->request->ajax();
        if ($ajax) {
            return Response::json(array_replace_recursive([
                'status' => $status,
                'msg' => $msg,
            ], $type), $status === 'success' ? 200 : 400);
        }
        Session::flash($status, $msg);
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

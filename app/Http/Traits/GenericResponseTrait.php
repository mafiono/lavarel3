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
     * @param $options
     * @return JsonResponse|RedirectResponse
     */
    private function respType($status, $msg, $options = null)
    {
        if ($options === null) $options = [
            'type' => $status
        ];
        if (is_string($options)) $options = [
            'type' => $options
        ];
        $ajax = $this->request->ajax();
        if ($ajax) {
            $options = array_replace_recursive([
                'status' => $status,
                'msg' => $msg,
            ], $options);
            return Response::json($options, $status === 'error' ? 400 : 200);
        }
        Session::flash($status, $msg);

        if ($options['type'] === 'redirect')
            return redirect($options['redirect']);
        else
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
            return Response::json([$type => $msg], $type === 'error' ? 400 : 200);
        }
        Session::flash($type, $msg);
        return back();
    }
}

<?php

namespace App\Http\Traits;

use Response, Session;

trait GenericResponseTrait
{
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

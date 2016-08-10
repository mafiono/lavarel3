<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Error;
use App\Models\UserComplain;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use App\Http\Requests;
use App\Models\UserProfile;
use App\Models\MessageType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Datatables;

class MessageTypesController extends Controller {

    public function index()
    {
        $wip = "Work In Progress";
        return view('messages.index', compact('wip'));
    }




    public function datatablesMessageTypes(Request $request, $id)
    {
        if ($request->ajax()) {

            $messages = MessageType::where('staff_id', '=', $id)->orderBy('id', 'desc');

            return Datatables::of($messages)
                ->editColumn('text', function ($message) {
                    return e($message->text);
                })
                ->editColumn('staff_id', function ($message) {
                    return e($message->staff_id);
                })
                ->editColumn('filter', function ($message) {
                    return e($message->filter);
                })
                ->editColumn('operator', function ($message) {
                    return e($message->operator);
                })
                ->editColumn('value', function ($message) {
                    return e($message->value);
                })
                ->editColumn('created_at', function ($message) {
                    return e($message->created_at);
                })

                ->make(true);
        }

    }







    /**
     *
     *
     * Transactions
     */


}

<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Error;
use App\Models\UserComplain;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use App\Http\Requests;
use App\Models\UserProfile;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session, View, Mail, Validator;
use Datatables;

class MessageController extends Controller {

    protected $authUser;

    protected $request;

    protected $userSessionId;
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function getMessages()
    {
        $id = auth::user()->id;
        $messages = Message::where('user_id', '=', $id)->orderBy('id', 'desc')->get();

        foreach($messages as $message)
        {

            $message->save();
        }

        return view('portal.communications.messages', compact('messages'));
    }

    public function readMessages()
    {
        $id = auth::user()->id;

        $messages = Message::where('user_id', '=', $id)->orderBy('id', 'asc')->get();

        foreach($messages as $message)
        {
            $message->viewed = 1;
            $message->save();
        }
        return $id;
    }
}

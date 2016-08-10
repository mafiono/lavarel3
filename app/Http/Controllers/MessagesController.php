<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Error;
use App\Models\MessageType;
use App\Models\UserComplain;
use App\User;
use Carbon\Carbon;
use App\Http\Requests;
use App\Models\UserProfile;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Image;

class MessagesController extends Controller {

    public function index()
    {
        $staff = Auth::user()->id;

        return view('messages.index', compact('staff'));
    }



    public function Chat()
    {

        $user = Auth::user()->id;
        $messages = Message::query()
            ->where('user_id','=',$user)
            ->get();


        return view('messages.chat', compact('messages'));
    }

    public function SendMessage(Request $request)
    {
        $user = Auth::user();
        $inputs = $request->all();
        $message = new Message;
        $message->user_id = $user->id;
        $message->sender_id = $user->id;
        $message->text = $inputs['message'];

        if($request->file('image')) {
            $pathToImg = $request->file('image');
            $data = file_get_contents($pathToImg);
            $base64 = 'data:image/' . 'jpeg' . ';base64,' . base64_encode($data);
            $message->image = $base64;
        }

        $message->sender_id = $user->id;
        $message->save();

        return back();
    }


}

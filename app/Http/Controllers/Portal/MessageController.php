<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseTrait;
use App\Models\Customer;
use App\Models\Error;
use App\Models\UserComplain;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\User;
use App\Http\Requests;
use App\Models\UserProfile;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Session, View, Mail, Validator;
use Datatables;

class MessageController extends Controller {

    use GenericResponseTrait;

    protected $authUser;

    protected $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();

        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function getMessages()
    {
        $messages = Message::query()
            ->leftJoin('staff as s', 's.id', '=', 'messages.staff_id')
            ->where('user_id','=',$this->authUser->id)
            ->select([
                'messages.id',
                'messages.created_at',
                'messages.text',
                's.name as staff'
            ])
            ->get();

        return view('portal.communications.messages', compact('messages'));
    }

    public function readMessages()
    {
        $messages = Message::where('user_id', '=', $this->authUser->id)
            ->where('viewed', '=', 0)
            ->orderBy('id', 'asc')
            ->get();

        $lastId = 0;
        foreach($messages as $message)
        {
            $lastId = $message->id;
            $message->viewed = 1;
            $message->save();
        }
        return $lastId;
    }


    public function getChat()
    {
        $messages = Message::query()
            ->leftJoin('staff as s', 's.id', '=', 'messages.staff_id')
            ->where('user_id','=',$this->authUser->id)
            ->select([
                'messages.id',
                'messages.created_at',
                'messages.text',
                's.name as staff'
            ])
            ->get();
        return response()->json($messages);
    }

    public function postNewMessage(Request $request)
    {
        $msg = $request->get('message');
        if ((empty($msg) || strlen($msg) < 3) && !$request->hasFile('image'))
            return $this->respType('error', 'Por favor escreva mais qualquer coisa.');

        try {
            DB::beginTransaction();

            $message = new Message;
            $message->user_id = $this->authUser->id;
            $message->sender_id = $this->authUser->id;

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $dataFile = file_get_contents($file->getRealPath());
                $message->image = $dataFile;
                if (!empty($msg)) $msg .= "\n";
                $msg = trim($msg . "Adicionado Imagem " . $file->getClientOriginalName());
            }
            $message->text = $msg;

            if (empty($message->text))
                return $this->resp('error', 'Preencha algo no texto!');

            $last = Message::query()
                ->where('user_id', '=', $this->authUser->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($last !== null && $last->text === $msg) {
                return $this->resp('empty', 'Mensagem Repetida');
            }

            $message->sender_id = $this->authUser->id;
            $message->save();

            DB::commit();
        } catch (Exception $e) {
            Log::error('Error saving message chat: '. $e->getMessage());
            DB::rollback();
            return $this->respType('error', 'Ocorreu um erro a gravar a mensagem, tente novamente.');
        }
        return $this->respType('empty', 'Mensagem gravada.');
    }

}

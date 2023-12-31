<?php

namespace App\Http\Controllers\Portal;

use App\Enums\ValidFileTypes;
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
        if (empty($msg) || strlen($msg) < 3)
            return $this->respType('error', 'Por favor escreva mais qualquer coisa.');

        try {
            DB::beginTransaction();

            $message = new Message;
            $message->user_id = $this->authUser->id;
            $message->sender_id = $this->authUser->id;
            $message->text = $msg;

            if (empty($message->text))
                throw new Exception('Preencha algo no texto!');

            $last = Message::query()
                ->where('user_id', '=', $this->authUser->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($last !== null && $last->text === $msg) {
                throw new Exception('Mensagem Repetida');
            }

            $message->sender_id = $this->authUser->id;
            $message->save();

            DB::commit();
        } catch (Exception $e) {
            Log::error('Error saving message chat: '. $e->getMessage());
            DB::rollback();
            return $this->respType('error', $e->getMessage());
        }
        return $this->respType('empty', 'Mensagem gravada.');
    }

    public function postNewUpload(Request $request)
    {
        if (!$request->hasFile('image'))
            return $this->respType('error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');

        try {
            DB::beginTransaction();

            $message = new Message;
            $message->user_id = $this->authUser->id;
            $message->sender_id = $this->authUser->id;

            $msg = '';
            if($request->hasFile('image')) {
                $file = $request->file('image');
                if (!ValidFileTypes::isValid($file->getMimeType()))
                    throw new Exception('Apenas são aceites imagens ou documentos no formato PDF ou WORD.');

                if ($file->getClientSize() > 5 * 1024 * 1024)
                    throw new Exception('O tamanho máximo aceite é de 5mb.');

                $dataFile = file_get_contents($file->getRealPath());
                $hash = $message->hash = sha1($dataFile);
                $last = Message::query()
                    ->where('user_id', '=', $this->authUser->id)
                    ->where('hash', '=', $hash)
                    ->first();
                if ($last !== null)
                    throw new Exception('Este documento já foi enviado!');

                $message->image = $dataFile;
                $msg = trim("Adicionado Imagem " . $file->getClientOriginalName());
            }
            if ($msg === '') {
                throw new Exception('Não foi possível adicionar a imagem!');
            }
            $message->text = $msg;

            if (empty($message->text))
                throw new Exception('Ocorreu um erro a enviar o documento, por favor tente novamente.');

            $message->sender_id = $this->authUser->id;
            $message->save();

            DB::commit();
        } catch (Exception $e) {
            Log::error('Error saving message image: '. $e->getMessage());
            DB::rollback();
            return $this->respType('error', $e->getMessage());
        }
        return $this->respType('empty', 'Documento gravado.');
    }
}

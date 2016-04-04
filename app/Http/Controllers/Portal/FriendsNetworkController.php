<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\JogadorConta;
use Input;

class FriendsNetworkController extends Controller
{

    protected $authUser;

    protected $request;

    protected $userSessionId;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display amigos/convites page
     *
     * @return \View
     */
    public function invitesGet()
    {
        return view('portal.friends.invites');
    }
    /**
     * Display amigos/rede page
     *
     * @return \View
     */
    public function network()
    {
        $friends = $this->authUser->friendInvites()->get();
        return view('portal.friends.network', compact('friends'));
    }

    /**
     * Send mail invites to user friends
     *
     * @return JsonResponse
     */
    public function invitesPost() {
        $emails = explode(',',Input::get('emails_list'));
        $rules = [];
        foreach ($emails as $key => $value)
            $rules[$key] = 'email';
        $validator = Validator::make($emails, $rules);
        if ($validator->fails())
            return Response::json(['status' => 'error', 'msg' => ['emails_list' => 'Os emails estão mal formatados.']]);
        try {

            foreach ($emails as $email) {
                $invite_message = nl2br(Input::get('emails_list_message'));
                Mail::send('portal.friends.emails.invites_message', ['invite_message' => $invite_message], function ($m) use ($email) {
                    $m->to($email)->subject('Convite para jogar');
                });
            }
        } catch (\Exception $e) {
            return Response::json( [ 'status' => 'error', 'type' => 'error', 'msg' => 'Erro ao enviar os emails.' ] );
        }
        Session::flash('success', 'Convites enviados com sucesso!');
        return Response::json(['status' => 'success', 'type' => 'reload']);
    }

    /**
     * Send email invites to user friends, those emails are queued.
     *
     * @return JsonResponse
     */
    public function inviteBulkPost() {
        $emails = json_decode(Input::get('emails_list'));
        $invite_message = "Olá, vem jogar na BetPortugal(http://betportugal.pt).";
//        dd($emails);
        foreach ($emails as $email) {
            if ($email) {
                Mail::queue('portal.friends.emails.invites_message', ['invite_message' => $invite_message], function ($m) use ($email) {
                    $m->to($email)->subject('Convite para jogar');
                });
            }
        }
    }
}

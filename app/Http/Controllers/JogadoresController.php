<?php

namespace App\Http\Controllers;

use View, Datatable;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Auth, File, Response, Mail, Session;
use App\Jogador, App\Documentacao, App\TipoEstadoConta;

class JogadoresController extends Controller {

    protected $authUser;

    protected $request;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //$this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display jogadores index page
     *
     * @return \View
     */
    public function index()
    {
        $jogadores = Jogador::all();

        return view('dashboard.jogadores.index', compact('jogadores'));
    }

    /**
     * Display comprovativos de morada de jogadores page
     *
     * @return \View
     */
    public function comprovativoMorada($jogador_id)
    {
        if (! $jogador = Jogador::find($jogador_id))
            return redirect()->intended('/dashboard/jogadores');

        $estados = TipoEstadoConta::lists('estado', 'id');

        return view('dashboard.jogadores.comprovativo_morada', compact('jogador', 'estados'));
    } 

    public function comprovativoMoradaPost($jogador_id)
    {
        if (! $jogador = Jogador::find($jogador_id))
            return Response::json(['status' => 'error', 'type' => 'redirect', 'redirect' => '/dashboard/jogadores']);        

        $inputs = $this->request->only('estado', 'email', 'observacoes');
        if (!empty($inputs['email']) && empty($inputs['observacoes']))
            return Response::json(['status' => 'error', 'msg' => ['observacoes' => 'Por favor preencha o texto do email']]);        

        if (!$jogador->jogadorConta->setEstadoById($inputs['estado']))
            return Response::json(['status' => 'error', 'type' => 'error', 'msg' => 'Ocorreu um erro ao alterar o estado do jogador']);
        
        if (!empty($inputs['email'])) {
           /*
            * Enviar email
            */
            try {
                Mail::send('dashboard.jogadores.emails.comprovativo_morada', ['observacoes' => $inputs['observacoes']], function ($m) use ($jogador) {
                    $m->to($jogador->email, $jogador->nome_completo)->subject('Autenticação de Morada');
                    $m->cc('joaoccmatos@gmail.com', 'João Matos');
                    $m->cc('luis.filipe.flima@gmail.com', 'Webhouse');
                });               
            } catch (\Exception $e) {
                //goes silent
            }            

            $msg = 'Estado alterado e email enviado com sucesso';
        }else
            $msg = 'Estado alterado com sucesso';

        Session::flash('success', $msg);

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }

    /**
     * Download a player document
     *
     * @return \View
     */
    public function downloadComprovativoMorada($documento_id)
    {
        if (! $documento = Documentacao::find($documento_id))
            return redirect()->back();

        $file = storage_path().DIRECTORY_SEPARATOR.'documentacao'.DIRECTORY_SEPARATOR.$documento->ficheiro;

        return Response::download($file);        
    }        

}

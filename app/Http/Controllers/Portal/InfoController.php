<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class InfoController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');
        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function index() {
        return view('portal.info.index');
    }

    public function aboutUs() {
        return view('portal.info.about_us');
    }

    public function affiliates() {
        return view('portal.info.affiliates');
    }

    public function terms() {
        return view('portal.info.terms');
    }

    public function contacts() {
        return view('portal.info.contacts');
    }

    public function help() {
        return view('portal.info.help');
    }

    public function promotions() {
        return view('portal.info.promotions');
    }

    public function faq() {
        return view('portal.info.faq');
    }

    public function restricted() {
        return view('portal.info.restricted');
    }

    public function pays() {
        return view('portal.info.pays');
    }

    public function politica_priv(){
        return view('portal.info.politica_priv');
    }

    public function politica_cookies(){
        return view('portal.info.politica_cookies');
    }

    public function regras(){
        return view('portal.info.regras');
    }

    public function dificuldades_tecnicas(){
        return view('portal.info.dificuldades_tecnicas');
    }

    public function jogo_responsavel(){
        return view('portal.info.jogo_responsavel');
    }
}
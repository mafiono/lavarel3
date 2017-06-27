<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\LegalDoc;
use App\Models\LegalDocVersion;
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
        $legalDoc = LegalDoc::getDoc('info');
        return view('portal.info.index', compact('legalDoc'));
    }

    public function affiliates() {
        $legalDoc = LegalDoc::getDoc('affiliates')->description;
        return view('portal.info.affiliates', compact('legalDoc'));
    }

    public function terms() {
        $legalDoc = LegalDoc::getDoc('terms')->description;
        return compact('legalDoc');
    }

    public function contacts() {
        $legalDoc = LegalDoc::getDoc('contacts')->description;
        return compact('legalDoc');
    }

    public function help() {
        $legalDoc = LegalDoc::getDoc('help');
        return view('portal.info.help', compact('legalDoc'));
    }

    public function faq() {
        $legalDoc = LegalDoc::getDoc('faq')->description;
        return compact('legalDoc');
    }

    public function pays() {
        $legalDoc = LegalDoc::getDoc('pays')->description;
        return compact('legalDoc');
    }

    public function protect_user(){
        $legalDoc = LegalDoc::getDoc('protect_user');
        return compact('legalDoc');
    }

    public function politica_priv(){
        $legalDoc = LegalDoc::getDoc('politica_priv')->description;
        return compact('legalDoc');
    }

    public function politica_cookies(){
        $legalDoc = LegalDoc::getDoc('politica_cookies');
        return view('portal.info.politica_cookies', compact('legalDoc'));
    }

    public function regras($tipo = 'sports', $game = 'index'){
        $legalDoc = LegalDoc::getDoc('rules.'.$tipo.'.'.$game);
        $childes = LegalDoc::getChildes('rules.'.$tipo);

        return view('portal.info.rules.regras', compact('tipo', 'game', 'childes', 'legalDoc'));
    }

    public function dificuldades_tecnicas(){
        $legalDoc = LegalDoc::getDoc('dificuldades_tecnicas');
        return view('portal.info.dificuldades_tecnicas', compact('legalDoc'));
    }

    public function jogo_responsavel(){
        $legalDoc = LegalDoc::getDoc('jogo_responsavel')->description;
        return compact('legalDoc');
    }

    public function adService($link)
    {

        $image = Ad::where('link',$link)->first()->image;
        $path = '\public\assets\img\ads' . '\' . $image;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return response(file_get_contents($path), 200)
            ->header('Content-Type', $mime);

    }

}
<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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

    public function aboutUs() {
        $legalDoc = LegalDoc::getDoc('about_us');
        return view('portal.info.about_us', compact('legalDoc'));
    }

    public function affiliates() {
        $legalDoc = LegalDoc::getDoc('affiliates');
        return view('portal.info.affiliates', compact('legalDoc'));
    }

    public function terms() {
        $legalDoc = LegalDoc::getDoc('terms');
        return view('portal.info.terms', compact('legalDoc'));
    }

    public function contacts() {
        $legalDoc = LegalDoc::getDoc('contacts');
        return view('portal.info.contacts', compact('legalDoc'));
    }

    public function help() {
        $legalDoc = LegalDoc::getDoc('help');
        return view('portal.info.help', compact('legalDoc'));
    }

    public function promotions() {
        $legalDoc = LegalDoc::getDoc('promotions');
        return view('portal.info.promotions', compact('legalDoc'));
    }

    public function faq() {
        $legalDoc = LegalDoc::getDoc('faq');
        return view('portal.info.faq', compact('legalDoc'));
    }

    public function pays() {
        $legalDoc = LegalDoc::getDoc('pays');
        return view('portal.info.pays', compact('legalDoc'));
    }

    public function politica_priv(){
        $legalDoc = LegalDoc::getDoc('politica_priv');
        return view('portal.info.politica_priv', compact('legalDoc'));
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
        $legalDoc = LegalDoc::getDoc('jogo_responsavel');
        return view('portal.info.jogo_responsavel', compact('legalDoc'));
    }
}
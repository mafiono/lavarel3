<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class BetsController extends Controller
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
        $this->middleware('auth', ['except' => ['index', 'sports', 'loadPost']]);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');
        View::share('authUser', $this->authUser, 'request', $request);
    }
    /**
     * Loads partial views from ajax requests
     *
     * @return void
     */
    public function loadPost($value)
    {
        if (!$value)
            return Response::json( ['status' => 'error', 'msg' => 'Ocorreu um erro a atualizar os dados.'] );
        $data = $this->request->all();
        if (!isset($data['code']) || $data['code'] != 0)
            return Response::json( ['status' => 'error', 'msg' => json_encode($data)] );
        switch ($value) {
            case 'leftbar':
                $view = View::make('portal.bets.leftbar', compact('data'));
                break;
            case 'middle':
                $marketsForSelect = [];
                $sportName = "sportName";
                $regionName = "regionName";
                $competitionName = "competitionName";
                $competition = "competition";
                if (!empty($data) && !empty($data['data'])) {
                    foreach ($data['data']['data']['sport'] as $key => $sport) {
                        $sportName = $sport['alias'];
                        foreach ($sport['region'] as $key1 => $region) {
                            $regionName = $region['name'];
                            foreach ($region['competition'] as $key2 => $competition) {
                                $competitionName = $competition['name'];
                                foreach ($competition['game'] as $key3 => $games) {
                                    if (!empty($games['market'])) {
                                        foreach ($games['market'] as $market) {
                                            if (!empty($market) && isset($market['id']))
                                                $marketsForSelect[$market['id']] = $market['name'];
                                        }
                                    }
                                    break;
                                }
                            }
                            break;
                        }
                        break;
                    }
                    $marketsForSelect = array_unique($marketsForSelect);
                }
                $view = View::make('portal.bets.middle', compact('sportName', 'regionName', 'competitionName', 'competition', 'marketsForSelect'));
                break;
            default:
                return Response::json( ['status' => 'error', 'msg' => 'Ocorreu um erro a atualizar os dados.'] );
        }
        $html = $view->render();
        return Response::json( array('status' => 'success', 'html' => $html) );
    }
    public function sports()
    {
        $phpAuthUser = $this->authUser?[
            "id" => $this->authUser->id,
            "auth_token" => $this->authUser->api_password
        ]:null;
        return view('portal.bets.sports', ["phpAuthUser" => $phpAuthUser]);
    }
    /**
     * Test connection to api partners
     *
     * @return \View
     */
    // public function index()
    // {
    //     /* Request a new session */
    //     $client = new Client();
    //     $url = 'http://swarm-partner.betconstruct.com';
    //     $params = [
    //         "command" => "request_session",
    //         "params" => [
    //             "site_id" => 234,
    //             "language" => "eng"
    //         ]
    //     ];
    //     $request = $client->createRequest('POST', $url);
    //     try {
    //         $response = $client->post($url, ['json' => $params])->json();
    //     } catch(\Exception $e){
    //          if($e->hasResponse()){
    //             dd($e->getResponse()->getReasonPhrase());
    //          }            
    //         dd($e->getMessage());
    //     }
    //     /* Store the Session ID */
    //     $sid = $response['data']['sid'];
    //     /* Get Champions League Matches and Events */
    //     $params = [
    //         "command" => "get",
    //         "params" => [
    //             "source" => "betting",
    //             "what" => ["sport" => [], "region" => [], "competition" => [], "game" => [], "market" => [], "event" => []],
    //             "where" => [
    //                 "competition" => ["id" => 1351379392]
    //             ],
    //             "subscribe" => false
    //         ]
    //     ];
    //     try {
    //         $response = $client->post($url, ['headers' => ['swarm-session' => $sid], 'json' => $params])->json();
    //     } catch(\Exception $e){
    //          if($e->hasResponse()){
    //             dd($e->getResponse()->getReasonPhrase());
    //          }            
    //         dd($e->getMessage());
    //     }        
    //     $results = $response['data']['data'];
    //     $sportName = $results['sport'][844]['alias'];
    //     $regionName = $results['sport'][844]['region'][65560]['name'];
    //     $competitionName = $results['sport'][844]['region'][65560]['competition'][1351379392]['name'];
    //     $games = $results['sport'][844]['region'][65560]['competition'][1351379392]['game'];
    //     return view('portal.bets.index', compact('sportName', 'regionName', 'competitionName', 'games'));
    // }                          
}

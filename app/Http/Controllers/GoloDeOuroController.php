<?php

namespace App\Http\Controllers;

use App\Bets\Bets\Bet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Models\Fixture;
use App\Bets\Models\Sport;
use App\Bets\Validators\BetslipBetValidator;
use App\Models\Golodeouro;
use App\Models\GolodeouroMarket;
use App\Models\GolodeouroSelection;
use App\Models\GolodeouroValue;
use App\UserBetEvent;
use App\UserSession;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Session, View, Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Handler\CurlFactory;
use GuzzleHttp\Client;

class GoloDeOuroController extends Controller
{

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

    protected function processBet($inputs)
    {
        $golo = Golodeouro::with('fixture')->where('id', $inputs['id'])->first();

        if($golo->fixture->start_time_utc > Carbon::now())
        {
            return response('O Jogo já começou', 400);
        }else {

            $bet = new Bet();
            $bet->user_id = Auth::user()->id;
            $bet->api_bet_type = 'golodeouro';
            $bet->amount = $inputs['valor'];
            $bet->result = 'waiting_result';
            $bet->status = 'waiting_result';
            $bet->user_session_id = UserSession::getSessionId();
            $bet->odd = Golodeouro::find($inputs['id'])->odd;
            $bet->type = 'multi';
            $bet->cp_fixture_id = $inputs['id'];


            if (BetslipBetValidator::make($bet)->validate()) {
                BetBookie::placeBet($bet);
                $eventmarcador = new UserBetEvent;
                $eventmarcador->user_bet_id = $bet->id;
                $eventmarcador->odd = GolodeouroSelection::find($inputs['marcador'])->odd;
                $eventmarcador->status = 'waiting_result';
                $eventmarcador->event_name = '';
                $eventmarcador->market_name = GolodeouroSelection::find($inputs['marcador'])->name;
                $eventmarcador->game_name = 'golodeouro';
                $eventmarcador->game_date = $golo->fixture->start_time_utc;
                $eventmarcador->api_event_id = $inputs['marcador'];
                $eventmarcador->api_game_id = $golo->fixture->id;

                $eventminuto = new UserBetEvent;
                $eventminuto->user_bet_id = $bet->id;
                $eventminuto->odd = GolodeouroSelection::find($inputs['minuto'])->odd;
                $eventminuto->status = 'waiting_result';
                $eventminuto->event_name = '';
                $eventminuto->market_name = GolodeouroSelection::find($inputs['minuto'])->name;
                $eventminuto->game_name = 'golodeouro';
                $eventminuto->game_date = $golo->fixture->start_time_utc;
                $eventminuto->api_event_id = $inputs['minuto'];
                $eventminuto->api_game_id = $golo->fixture->id;

                $eventresultado = new UserBetEvent;
                $eventresultado->user_bet_id = $bet->id;
                $eventresultado->odd = GolodeouroSelection::find($inputs['resultado'])->odd;
                $eventresultado->status = 'waiting_result';
                $eventresultado->event_name = '';
                $eventresultado->market_name = GolodeouroSelection::find($inputs['resultado'])->name;
                $eventresultado->game_name = 'golodeouro';
                $eventresultado->game_date = $golo->fixture->start_time_utc;
                $eventresultado->api_event_id = $inputs['resultado'];
                $eventresultado->api_game_id = $golo->fixture->id;

                $eventmarcador->save();
                $eventminuto->save();
                $eventresultado->save();

                return response('Success', 200);
            }
            return response('Error', 400);
        }
    }

    public function index()
    {

    }

    public function aposta(Request $request)
    {
        if ($request->get('marcador') == '' || $request->get('valor') == '' || $request->get('id') == '' || $request->get('minuto') == '' || $request->get('resultado') == '') {
            return abort(400);
        } else {
            return $this->processBet($request);
        }
    }

    public function getApiActive()
    {
        $url = '/api/v1/goldengoal/active';

        return $this->makeRequestGeneric($url);
    }

    public function getApiSelections($id, $name)
    {
        $url = '/api/v1/goldengoal/'.$id.'/markets/'. $name . '/selections';

        return $this->makeRequestGeneric($url);
    }

    public function getApiValues($id)
    {
        $url = '/api/v1/goldengoal/'.$id.'/values';

        return $this->makeRequestGeneric($url);
    }

    public function getApiInactives()
    {
        $url = '/api/v1/goldengoal/lastactive';

        return $this->makeRequestGeneric($url);
    }

    private function makeRequestGeneric($url)
    {
        $baseApi = config('app.core_api_url');
        $client = new Client([
            'verify' => false,
            'json' => true,
        ]);

        try {
            $obj = $client->get($baseApi . $url);


            $resp = $obj->getBody()->getContents();

            return response($resp, 200, [
                'Content-Type' => 'application/json'
            ]);
        } catch (ClientException $e) {
            $resp = $e->getResponse()->getBody()->getContents();

            return response($resp, $e->getCode(), [
                'Content-Type' => 'application/json'
            ]);
        }
    }
}

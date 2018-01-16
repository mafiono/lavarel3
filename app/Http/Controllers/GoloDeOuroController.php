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
use Illuminate\Support\Facades\DB;
use Session, View, Auth;
use Illuminate\Http\Request;

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
        $golo = Golodeouro::with('fixture')->where('id',$inputs['id'])->first();
        $bet = new Bet();
        $bet->user_id = Auth::user()->id;
        $bet->api_bet_type = 'golodeouro';
        $bet->amount = $inputs['valor'];
        $bet->result = 'waiting_result';
        $bet->status = 'waiting_result';
        $bet->user_session_id = UserSession::getSessionId();
        $bet->odd = Golodeouro::find($inputs['id'])->odd;
        $bet->type = 'multi';
        $bet->golodeouro_id = $inputs['id'];
        if(BetslipBetValidator::make($bet)->validate()) {
            BetBookie::placeBet($bet);

            $eventmarcador = new UserBetEvent;
            $eventmarcador->user_bet_id = $bet->id;
            $eventmarcador->odd = number_format($golo->odd / 3, 2);
            $eventmarcador->status = 'waiting_result';
            $eventmarcador->event_name = '';
            $eventmarcador->market_name = GolodeouroSelection::find($inputs['marcador'])->name;
            $eventmarcador->game_name = 'golodeouro';
            $eventmarcador->game_date = $golo->fixture->start_time_utc;
            $eventmarcador->api_event_id = $inputs['marcador'];
            $eventmarcador->api_game_id = $golo->fixture->id;

            $eventminuto = new UserBetEvent;
            $eventminuto->user_bet_id = $bet->id;
            $eventminuto->odd = number_format($golo->odd / 3, 2);
            $eventminuto->status = 'waiting_result';
            $eventminuto->event_name = '';
            $eventminuto->market_name = GolodeouroSelection::find($inputs['minuto'])->name;
            $eventminuto->game_name = 'golodeouro';
            $eventminuto->game_date = $golo->fixture->start_time_utc;
            $eventminuto->api_event_id = $inputs['minuto'];
            $eventminuto->api_game_id = $golo->fixture->id;

            $eventresultado = new UserBetEvent;
            $eventresultado->user_bet_id = $bet->id;
            $eventresultado->odd = number_format($golo->odd / 3, 2);
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
        }
    }

    public function index()
    {

    }

    public function marcadorMarket()
    {
        $selections = GolodeouroSelection::from(GolodeouroSelection::alias('selection'))
            ->leftJoin(GolodeouroMarket::alias('market'), 'market.id', '=', 'selection.golodeouro_market_id')
            ->leftJoin(Golodeouro::alias('golo'), 'market.golodeouro_id', '=', 'golo.id')
            ->leftJoin(Fixture::alias('fixture'), 'golo.fixture_id', '=', 'fixture.id')
            ->where('market.name','Marcador')
            ->where('golo.status','active')
            ->orderBy('selection.name','asc')
            ->select([
                DB::raw('selection.name as name'),
                DB::raw('selection.id as id'),
            ])->get();
        return $selections;
    }

    public function tempoMarket()
    {
        $selections = GolodeouroSelection::from(GolodeouroSelection::alias('selection'))
            ->leftJoin(GolodeouroMarket::alias('market'), 'market.id', '=', 'selection.golodeouro_market_id')
            ->leftJoin(Golodeouro::alias('golo'), 'market.golodeouro_id', '=', 'golo.id')
            ->leftJoin(Fixture::alias('fixture'), 'golo.fixture_id', '=', 'fixture.id')
            ->where('market.name','Minuto Primeiro Golo')
            ->where('golo.status','active')
            ->select([
                DB::raw('selection.name as name'),
                DB::raw('selection.id as id'),
            ])->get();
        return $selections;
    }
    public function resultadoMarket()
    {
        $selections = GolodeouroSelection::from(GolodeouroSelection::alias('selection'))
            ->leftJoin(GolodeouroMarket::alias('market'), 'market.id', '=', 'selection.golodeouro_market_id')
            ->leftJoin(Golodeouro::alias('golo'), 'market.golodeouro_id', '=', 'golo.id')
            ->leftJoin(Fixture::alias('fixture'), 'golo.fixture_id', '=', 'fixture.id')
            ->where('market.name','Resultado Final')
            ->where('golo.status','active')
            ->select([
                DB::raw('selection.name as name'),
                DB::raw('selection.id as id'),
            ])->get();
        return $selections;
    }

    public function goloDeOuro()
    {
        $selections = Golodeouro::from(Golodeouro::alias('golo'))
            ->leftJoin(Fixture::alias('fixture'), 'golo.fixture_id', '=', 'fixture.id')
            ->leftJoin(Sport::alias('sport'), 'fixture.sport_id', '=', 'sport.id')
            ->leftJoin(GolodeouroValue::alias('value'), 'value.golodeouro_id', '=', 'golo.id')
            ->where('golo.status','active')
            ->select([
                DB::raw('fixture.name as name'),
                DB::raw('fixture.external_id as fixtureID'),
                DB::raw('golo.id as id'),
                DB::raw('fixture.start_time_utc as start'),
                DB::raw('sport.name as sport'),
                DB::raw('golo.odd as odd'),
                DB::raw('max(value.amount) as max')
            ])->get()->take(1);
        return $selections;
    }

    public function goloDeOuroValues()
    {
        $values = GolodeouroValue::from(GolodeouroValue::alias('value'))
            ->leftJoin(Golodeouro::alias('golo'), 'value.golodeouro_id', '=', 'golo.id')
            ->where('golo.status','active')
            ->select([
                DB::raw('value.amount as amount'),
            ])->get();
        return $values;
    }

    public function historyGolos()
    {
        $selections = GolodeouroSelection::from(GolodeouroSelection::alias('selection'))
            ->leftJoin(GolodeouroMarket::alias('market'), 'market.id', '=', 'selection.golodeouro_market_id')
            ->leftJoin(Golodeouro::alias('golo'), 'golo.id', '=', 'market.golodeouro_id')
            ->leftJoin(Fixture::alias('fixture'), 'golo.fixture_id', '=', 'fixture.id')
            ->groupBy('selection.id')
            ->where('golo.status','inactive')
            ->orderBy('fixture.start_time_utc','DESC')
            ->where('selection.result_status','won')
            ->select([
                DB::raw('selection.name as name'),
                DB::raw('market.name as market'),
                DB::raw('fixture.name as nome'),
                DB::raw('fixture.start_time_utc as start'),
            ])->get()->take(3);
        return $selections;
    }

    public function aposta(Request $request)
    {
        if($request->get('marcador') == '' || $request->get('valor') == ''|| $request->get('id')== '' || $request->get('minuto')== '' || $request->get('resultado')== '')
        {
            return abort(400);
        }
        else{
           return $this->processBet($request);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Bets\Bets\Bet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Validators\BetslipBetValidator;
use App\Http\Traits\GenericResponseTrait;
use App\Models\Golodeouro;
use App\Models\GolodeouroSelection;
use App\Models\GolodeouroValue;
use App\UserBet;
use App\UserBetEvent;
use App\UserSession;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Exception\ClientException;
use Session, View, Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GoloDeOuroController extends Controller
{
    use GenericResponseTrait;
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
        try {
            $golo = Golodeouro::with('fixture')
                ->where('status', '=', 'active')
                ->where('id', '=', $inputs['id'])
                ->first();

            $bets = UserBet::where('cp_fixture_id',$inputs['id'])
                ->where('user_id', Auth::user()->id)
                ->count();

            if ($golo === null)
            {
                return $this->resp('error', 'Este Golo D\'Ouro ja nao se encontra ativo!');
            }
            if($bets >= $golo->max_bets)
            {
                return $this->resp('error', 'Atingiu o máximo de apostas neste Golo D\'Ouro!');
            }
            if (Carbon::parse($golo->fixture->start_time_utc, 'UTC') <= Carbon::now()->tz('UTC'))
            {
                return $this->resp('error', 'O Jogo ja começou!');
            }

            DB::beginTransaction();

            $bet = new Bet();
            $bet->user_id = Auth::user()->id;
            $bet->api_bet_type = 'golodeouro';
            $bet->amount = $inputs['valor'];
            $bet->result = 'waiting_result';
            $bet->status = 'waiting_result';
            $bet->user_session_id = UserSession::getSessionId();
            $bet->odd = $golo->odd;
            $bet->type = 'multi';
            $bet->cp_fixture_id = $inputs['id'];

            if(!GolodeouroValue::query()
                ->where('cp_fixture_id',$golo->id)
                ->where('amount',$inputs['valor'])
                ->exists())
            {
                return $this->resp('error', 'Ocorreu um erro ao validar o Golo D\'Ouro, por favor tente novamente!');
            }

            if (!BetslipBetValidator::make($bet)->validate()) {
                return $this->resp('error', 'Ocorreu um erro ao validar o Golo D\'Ouro, por favor tente novamente!');
            }
            $selectionMarcador = GolodeouroSelection::find($inputs['marcador']);
            $selectionMinuto = GolodeouroSelection::find($inputs['minuto']);
            $selectionResultado = GolodeouroSelection::find($inputs['resultado']);

            if($selectionMinuto === null || $selectionMarcador === null || $selectionResultado === null)
            {
                return $this->resp('error', 'Não foi possível encontrar o Golo D\'Ouro, por favor tente novamente!');
            }
            BetBookie::placeBet($bet);

            $eventMarcador = new UserBetEvent;
            $eventMarcador->user_bet_id = $bet->id;
            $eventMarcador->odd = $selectionMarcador->odd;
            $eventMarcador->status = 'waiting_result';
            $eventMarcador->event_name = $selectionMarcador->name;
            $eventMarcador->market_name = 'Primeiro Marcador';
            $eventMarcador->game_name = $golo->fixture->name;
            $eventMarcador->game_date = $golo->fixture->start_time_utc;
            $eventMarcador->api_event_id = $selectionMarcador->id;
            $eventMarcador->api_market_id = $selectionMarcador->cp_market_id;
            $eventMarcador->api_game_id = $golo->fixture->id;

            $eventMinuto = new UserBetEvent;
            $eventMinuto->user_bet_id = $bet->id;
            $eventMinuto->odd = $selectionMinuto->odd;
            $eventMinuto->status = 'waiting_result';
            $eventMinuto->event_name = $selectionMinuto->name;
            $eventMinuto->market_name = 'Minuto Primeiro Golo';
            $eventMinuto->game_name = $golo->fixture->name;
            $eventMinuto->game_date = $golo->fixture->start_time_utc;
            $eventMinuto->api_event_id = $selectionMinuto->id;
            $eventMinuto->api_market_id = $selectionMinuto->cp_market_id;
            $eventMinuto->api_game_id = $golo->fixture->id;

            $eventResultado = new UserBetEvent;
            $eventResultado->user_bet_id = $bet->id;
            $eventResultado->odd = $selectionResultado->odd;
            $eventResultado->status = 'waiting_result';
            $eventResultado->event_name = $selectionResultado->name;
            $eventResultado->market_name = 'Resultado Correcto';
            $eventResultado->game_name = $golo->fixture->name;
            $eventResultado->game_date = $golo->fixture->start_time_utc;
            $eventResultado->api_event_id = $selectionResultado->id;
            $eventResultado->api_market_id = $selectionResultado->cp_market_id;
            $eventResultado->api_game_id = $golo->fixture->id;

            $eventMarcador->save();
            $eventMinuto->save();
            $eventResultado->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->resp('error', 'Ocorreu um erro ao gravar o Golo D\'Ouro, por favor tente novamente!');
        }
        return $this->resp('Success', 'Aposta efetuada com sucesso!');
    }

    public function aposta(Request $request)
    {
        if ($request->get('marcador') == ''
            || $request->get('valor') == ''
            || $request->get('id') == ''
            || $request->get('minuto') == ''
            || $request->get('resultado') == ''
        ) {
            return $this->resp('error', 'Por favor preencha todos os campos da Aposta!');
        }
        return $this->processBet($request);
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

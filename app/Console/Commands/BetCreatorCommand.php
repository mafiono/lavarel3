<?php

namespace app\Console\Commands;


use App\Bets\Bets\BetslipBet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Collectors\BetslipCollector;
use App\Bets\Models\Selection;
use App\Bets\Resolvers\BetResolver;
use App\Bets\Validators\BetslipBetValidator;
use App\User;
use App\UserBetslip;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;


class BetCreatorCommand extends Command
{
    protected $signature = 'create-all-bets';

    protected $description = 'Creates a bet for all markets';

    public function handle()
    {
        $user = User::findById(404);
        $sessionId = $user->getLastSession()->id;

        $query = DB::table('betgenius.selections as s')
            ->leftJoin('betgenius.markets as m', 's.market_id', '=', 'm.id')
            ->leftJoin('betgenius.fixtures as f', 'm.fixture_id', '=', 'f.id')
            ->leftJoin('betportugal.user_bet_events as be', 'be.api_event_id', '=', 's.id')
            ->select([
                's.id',
                's.name',
                's.decimal as odds',
                'm.id as marketId',
                'm.name as marketName',
                'f.id as gameId',
                'f.name as gameName',
                'f.start_time_utc as gameDate',
            ])
            ->whereNull('be.id')
            ->where('s.decimal', '>', 1.08)
            ->whereIn('m.market_type_id', [2, 322, 306, 6662, 7469, 8133, 15, 6734,])
            ->where('f.status', '=', 'Scheduled')
            ->where('f.fixture_type', '=', 'Match')
            //->where('f.start_time_utc', '>', Carbon::now()->tz('UTC')->subHours(12))
            ->whereNotNull('f.srij_event_id')
            ->where('f.start_time_utc', '>', Carbon::now()->tz('UTC'))
            //->limit(10)
            ;
        //dd($query->count());

        $apostas = $query
            // ->limit(200)
            ->get();

        foreach ($apostas as $betSel) {
            try {
                DB::transaction(function () use($betSel, $user, $sessionId) {
                    $betArray = [
                        "rid" => $betSel->id, // bet id -> selection ID from betgenius
                        "amount" => 2, // valor da aposta 2 euros
                        "type" => "simple", // apenas vamos criar simples
                        "events" => [[
                            "id" => $betSel->id, // selection ID
                            "odds" => $betSel->odds, // valor da Odd
                            "name" => $betSel->name, // Casa, Fora etc :(
                            "marketId" => $betSel->marketId, // id do market
                            "marketName" => $betSel->marketName, // Nome do mercado :(
                            "gameId" => $betSel->gameId, // id do fixture
                            "gameName" => $betSel->gameName, // nome do Jogo
                            "gameDate" => $betSel->gameDate, // Data do jogo
                        ]]
                    ];
                    $newBetslip = UserBetslip::create(['user_id' => $user->id]);
                    // for 1 bet it is
                    $bet = BetslipBet::make($betArray, $newBetslip->id, $user, $sessionId);

                    BetslipBetValidator::make($bet)->validate();

                    BetBookie::placeBet($bet, $sessionId);

                });
            } catch (\Exception $e) {
                $this->line("Error: " . $betSel->id . ': ' . $betSel->gameName . " -> " . $e->getMessage());
            }
        }
        $this->line("Finish");
    }
}
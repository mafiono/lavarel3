<?php

namespace app\Console\Commands;


use App\Models\Golodeouro;
use App\Models\GoloDeOuroStats;
use App\UserBet;
use Illuminate\Console\Command;


class GoloDeOuroStatistics extends Command
{
    protected $signature = 'golodeouro-stats';

    protected $description = 'Estatisticas golodeouro';

    public function handle()
    {
            $goals = Golodeouro::all();

            foreach($goals as $goal)
            {
                $stat = GoloDeOuroStats::where('cp_fixture_id',$goal->id)->first();

                if(!isset($stat))
                {
                    $stat = new GoloDeOuroStats;
                }

                $bets = UserBet::where('golodeouro_id',$goal->id)->get();
                $stat->name = $goal->fixture->name;
                $stat->start_time = $goal->fixture->start_time_utc;
                $stat->odd = $goal->odd;
                $stat->bets_number = $bets->count();
                $stat->bet_amount = $bets->sum('amount');
                $stat->average = number_format($bets->sum('amount')/($bets->count()? $bets->count() : 1),2);
                $stat->ggr = $bets->sum('amount') - $bets->sum('result_amount');
                $stat->ggr_tax = number_format(($bets->sum('amount') * 0.08),2);
                $stat->paid = $bets->sum('result_amount');
                $stat->proft = $stat->ggr - $stat->ggr_tax;
                $stat->winners = UserBet::where('golodeouro_id',$goal->id)->where('status','won')->count();
                $stat->cp_fixture_id = $goal->id;
                $stat->cp_fixture_type = 'golodeouro';
                $stat->save();
                $stat->proft_historical = GoloDeOuroStats::where('id','<',$stat->id)->sum('proft')+$stat->proft;
                $stat->save();
            }
    }
}
<?php

namespace App\Console\Commands;

use App\Models\CasinoTransaction;
use App\User;
use App\UserTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class CheckBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all Balance of Users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    

        $result = DB::table('users')
            ->get(['users.id', 'users.username']);
        foreach ($result as $r){
            // $this->line('Process user: '.$r->id);
            $this->processUserBalance($r->id);
        }
    }

    private function processUserBalance($userId){
        /* @var $result UserTransaction[] */
        $result = DB::table('user_transactions')
            ->where('user_transactions.user_id', '=', $userId)
            ->orderBy('user_transactions.date', 'ASC')
            ->get(['debit', 'credit', 'status_id']);

        $av = 0;
        $ca = 0;
        $ac = 0;
        $bo = 0;
        $to = 0;

        foreach($result as $item){
            $val = $item->debit - $item->credit;
            switch ($item->status_id){
                case 'processed':
                    $av += $val;
                    $ac += $val;
                    $to += $val;
                    break;
                case 'on_hold':
                case 'pending':
                case 'approved':
                case 'delayed':
                    $av += $val;
                    $ca += $item->credit - $item->debit;
                    break;
                case 'declined':
                case 'canceled':
                    break;
                default:
                    $this->line('Unknown Status Id: '. $item->status_id .' User: '.$userId);
                    break;
            }


        }
        $resultBets = DB::table('user_bets')
            ->where('user_bets.user_id', '=', $userId)
            ->orderBy('user_bets.updated_at', 'ASC')
            ->get(['amount', 'result_amount', 'result', 'status']);
        foreach($resultBets as $item) {
            $val = ($item->result_amount ? $item->result_amount : 0) - $item->amount;
            $betamount = $item->amount;
            switch ($item->status){
                case null:
                    break;
                case 'won':
                $av += $val - $betamount;
                $ac += $val - $betamount;
                $to += $val - $betamount;
                    break;
                case 'waiting_result':
                    $av -= $betamount;
                    $ac -= $betamount;
                    $to -= $betamount;
                    break;
                case 'lost':
                    $av -= $betamount;
                    $ac -= $betamount;
                    $to -= $betamount;
                case 'returned':
                    $av += 0;
                    $ac += 0;
                    $to += 0;
                    break;
                default:
                    $this->line('Unknown Bet Status Id: '. $item->status .' User: '.$userId. 'BetId :'. $item->id);
                    break;
            }
        }

        $casinoBets = CasinoTransaction::query()->where('user_id','=',$userId)->get();

        foreach($casinoBets as $casinoBet){
            $val = $casinoBet->amount;
            switch ($casinoBet->type){
                case null:
                    break;
                case 'bet':
                    $av -= $val;
                    $ac -= $val;
                    $to -= $val;
                    break;
                case 'win':
                    $av += $val;
                    $ac += $val;
                    $to += $val;
                    break;
                default:
                    $this->line('Unknown Casino Status Id: '. $casinoBet->type .' User: '.$userId . 'BetId :'. $casinoBet->id);
            }

        }

        $balance = User::findById($userId)->balance;
        $balance->b_av_check = $balance->balance_available - $av;
        $balance->b_ac_check = $balance->balance_accounting - $ac;
        $balance->b_bo_check = $balance->balance_bonus - $bo;
        $balance->b_to_check = $balance->balance_total - $to;
        $balance->save();

        if ($balance->b_av_check != 0 ||
            $balance->b_ac_check != 0 ||
            $balance->b_bo_check != 0 ||
            $balance->b_to_check != 0){

            $this->line('User Id: '. $userId .' Has Invalid Balance:');
            $this->line('     Total: '.$balance->balance_total.' <-> '.$balance->b_to_check);
            $this->line('     Available: '.$balance->balance_available.' <-> '.$balance->b_av_check);
            $this->line('     Accounting: '.$balance->balance_accounting.' <-> '.$balance->b_ac_check);
            $this->line('     Bonus: '.$balance->balance_bonus.' <-> '.$balance->b_bo_check);
        }
    }
}

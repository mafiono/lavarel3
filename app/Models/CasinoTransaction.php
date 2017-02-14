<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CasinoTransaction extends Model
{
    use CasinoDatabase;

    protected $table = 'transactions';

    public function game()
    {
        return $this->belongsTo(CasinoGame::class);
    }

    public function round()
    {
        return $this->belongsTo(CasinoRound::class);
    }

    public function scopeNotFreeRound($query)
    {
        return $query->where('fround_id', 0);
    }

    public static function dailyAmount($user_id)
    {
        $amount = static::where('user_id', $user_id)
            ->whereTransactionstatus('ok')
            ->notFreeRound()
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->sum('amount');

        return round($amount/100, 2);
    }

    public static function weeklyAmount($user_id)
    {

        $amount = self::where('user_id', $user_id)
            ->whereTransactionstatus('ok')
            ->notFreeRound()
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->sum('amount');

        return round($amount/100, 2);
    }

    public static function monthlyAmount($user_id)
    {
        $amount = self::where('user_id', $user_id)
            ->whereTransactionstatus('ok')
            ->notFreeRound()
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');

        return round($amount/100, 2);
    }
}

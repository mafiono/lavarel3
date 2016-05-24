<?php

namespace App;

use App\Bets\Bet;
use App\Bets\CollectorReceipt;
use Illuminate\Database\Eloquent\Model;


class UserBetTransaction extends Model {
    protected $table = 'user_bet_transactions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status() {
        return $this->belongsTo('App\UserBetStatus', 'user_bet_status_id', 'id');
    }

}

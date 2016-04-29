<?php

namespace App;

use App\Bets\Bet;
use App\Bets\CollectorReceipt;
use Illuminate\Database\Eloquent\Model;


class UserBetTransactions extends Model {
    protected $table = 'user_bet_transactions';

    /**
     * @param Bet $bet
     * @return bool
     */
    private static function isBetPortugalApi(Bet $bet)
    {
        return $bet->getApiType() === 'betportugal';
    }

    /**
     * @param $trans
     */
    private static function setSelfApiId(UserBetTransactions $trans)
    {
        $trans->api_transaction_id = $trans->id;
        $trans->save();

        $userBet = $trans->userBetStatus->userBet;
        $userBet->api_transaction_id = $trans->id;
        $userBet->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userBetStatus() {
        return $this->belongsTo('App\UserBetStatus', 'user_bet_status_id', 'id');
    }

    /**
     * @param CollectorReceipt $receipt
     * @param $operation
     * @return UserBetTransactions
     */
    public static function createTransaction(Bet $bet, CollectorReceipt $receipt, $operation, $betStatusId) {
        $trans = new UserBetTransactions();
        $trans->user_bet_id = $receipt->getOwnerId();
        $trans->api_transaction_id = $bet->getApiTransactionId();
        $trans->operation = $operation;
        $trans->amount_balance = $receipt->getAmountBalance();
        $trans->amount_bonus = $receipt->getAmountBonus();
        $trans->initial_balance = $receipt->getInitialBalance();
        $trans->final_balance = $receipt->getFinalBalance();
        $trans->initial_bonus = $receipt->getInitialBonus();
        $trans->final_bonus = $receipt->getFinalBonus();
        $trans->user_bet_status_id = $betStatusId;
        $trans->save();

        if (self::isBetPortugalApi($bet))
            self::setSelfApiId($trans);

    }

}

<?php

namespace App\Bets\Cashier;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\PaymentCalculator\Calculator as PaymentCalculator;
use App\Events\BetWasResulted;
use App\Events\BetWasWagered;
use App\Lib\Mail\SendMail;
use SportsBonus;

class BetCashier
{

    public static function pay(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeDeposit($bet);

        $payment = PaymentCalculator::make($bet);

        $amountToBalance = $payment->balance();

        $amountToBonus = $payment->bonus();

//        $transaction = $bet->waitingResultStatus->transaction;
//
//        $amountFromBonus =  $transaction->amount_bonus*$bet->odd;
//
//        $amountFromBalance = $transaction->amount_balance*$bet->odd;
//
//        $totalAmount = $amountFromBonus + $amountFromBalance;

        if ($amountToBalance > 0) {
            $bet->user->balance->addAvailableBalance($amountToBalance);
        }

        if ($amountToBonus > 0) {
            $bet->user->balance->addAvailableBonus($amountToBonus);
        }

        $receipt->amount_balance = $amountToBalance;

        $receipt->amount_bonus = $amountToBonus;

        $receipt->store();

        event(new BetWasResulted($bet, $receipt));
    }

    public static function noPay(Bet $bet)
    {
        ($receipt = BetCashierReceipt::makeDeposit($bet))->store();

        event(new BetWasResulted($bet, $receipt));
    }

    public static function charge(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeWithdrawal($bet);

        $bill = new ChargeCalculator($bet, SportsBonus::applicableTo($bet));

        $amountBalance = $bill->balanceAmount;

        $amountBonus = $bill->bonusAmount;

        $bet->user->balance->subtractAvailableBalance($amountBalance);

        if ($amountBonus > 0) {
            $bet->user->balance->subtractBonus($amountBonus);

            $bet->user_bonus_id = SportsBonus::userBonus()->id;

            $bet->save();
        }

        $receipt->amount_balance = $amountBalance;

        $receipt->amount_bonus = $amountBonus;

        $receipt->store();

        event(new BetWasWagered($bet, $receipt));
    }

    public static function refund(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeDeposit($bet);

        $transaction = $bet->waitingResultStatus->transaction;

        $amountBalance = $transaction->amount_balance;

        $amountBonus =  0;

        $bet->user->balance->addAvailableBalance($amountBalance);

        $receipt->amount_balance = $amountBalance;

        if (SportsBonus::isAppliedToBet($bet)) {
            $amountBonus =  $transaction->amount_bonus;

            $bet->user->balance->addBonus($amountBonus);

            $receipt->amount_bonus = $amountBonus;
        }

        $receipt->store();

        event(new BetWasResulted($bet, $receipt));

        // Send Email to User
        $mail = new SendMail(SendMail::$TYPE_9_BET_RETURNED);
        $mail->prepareMail($bet->user, [
            'title' => 'Aposta devolvida ou cancelada',
            'nr' => $bet->id,
            'value' => number_format($amountBalance + $amountBonus, 2, ',', ' '),
        ]);
        $mail->Send(false);
    }
}

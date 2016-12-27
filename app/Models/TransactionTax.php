<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionTax
 * @property int id
 * @property string method
 * @property string transaction_id
 * @property string type
 * @property int process_time_min
 * @property int process_time_max
 * @property double tax
 * @property int free_above
 * @property int free_days
 * @property int min
 * @property int max
 * @property int staff_id
 * @property int staff_session_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TransactionTax extends Model
{
    protected $table = 'transactions_taxes';

    public static function getByMethod($method)
    {
        $list = [];
        $items = self::query()->where('method', '=', $method)->get();
        foreach ($items as $item) {
            $list[$item->transaction_id] = $item;
        }
        return $list;
    }

    /**
     * @param $transaction
     * @param $method
     * @return TransactionTax | null
     */
    public static function getByTransaction($transaction, $method)
    {
        return self::query()
            ->where('transaction_id', '=', $transaction)
            ->where('method', '=', $method)
            ->first();
    }

    public function staff() {
        return $this->hasOne('\App\Models\Staff', 'id', 'staff_id');
    }

    public function transaction() {
        return $this->hasOne('\App\Transaction', 'id', 'transaction_id');
    }
    public function calcTax($amount) {
        if ($amount < $this->min)
            throw new Exception('O valor minimo de depósito é '. $this->min);
        if ($amount >= $this->free_above)
            return 0;
        if ($this->tax <= 0)
            return 0;

        $tax = $amount * ($this->tax / 100);
        $tax = round($tax, 2, PHP_ROUND_HALF_UP);
        return $tax;
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionTax
 * @param id int
 * @param method string
 * @param transaction_id string
 * @param type string
 * @param process_time_min int
 * @param process_time_max int
 * @param tax double
 * @param free_above int
 * @param free_days int
 * @param min int
 * @param max int
 * @param staff_id int
 * @param staff_session_id int
 * @param created_at Carbon
 * @param updated_at Carbon
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

    public function staff() {
        return $this->hasOne('\App\Models\Staff', 'id', 'staff_id');
    }

    public function transaction() {
        return $this->hasOne('\App\Transaction', 'id', 'transaction_id');
    }
}

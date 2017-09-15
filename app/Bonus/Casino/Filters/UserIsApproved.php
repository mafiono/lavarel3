<?php

namespace App\Bonus\Casino\Filters;

use Illuminate\Database\Eloquent\Collection;

class UserIsApproved extends Filter
{
    public function run()
    {
        if (!$this->user->status->isApproved()) {
            $this->data = new Collection();
        }
    }
}
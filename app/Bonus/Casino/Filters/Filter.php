<?php

namespace app\Bonus\Casino\Filters;

use App\User;
use Illuminate\Database\Eloquent\Collection;

abstract class Filter
{
    protected $user;

    protected $data;

    public function __construct(User $user = null)
    {
        $this->user = $user;

        $this->data = new Collection();
    }

    public function filter(Filter $filter = null)
    {
        $filter->data($this->data);

        $filter->run();

        $this->data = $filter->data();

        return $this;
    }

    public function combine(array $filters, $uniqueKey = null)
    {
        $newData = new Collection();;

        foreach ($filters as $filter) {
            $filter->data($this->data);

            $filter->run();

            $newData = is_null($uniqueKey)
                ? $newData->merge($filter->data)
                : $newData->merge($filter->data)->unique($uniqueKey);
        }

        $this->data = new $newData;

        return $this;
    }

    public function data($data = null)
    {
        if (is_null($data)) {
            return $this->data;
        }

        $this->data = $data;
    }

    abstract public function run();
}
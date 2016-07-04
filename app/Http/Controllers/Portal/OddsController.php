<?php

namespace App\Http\Controllers\Portal;

use App\Http\OddsRedirector\OddsRedirector;
use App\Http\Controllers\Controller;

class OddsController extends Controller
{
    public function fixtures(OddsRedirector $redirect)
    {
        return $redirect->to('fixtures');
    }

    public function sports(OddsRedirector $redirect)
    {
        return $redirect->to('sports');
    }

    public function regions(OddsRedirector $redirect)
    {
        return $redirect->to('regions');
    }

    public function competitions(OddsRedirector $redirect)
    {
        return $redirect->to('competitions');
    }

    public function markets(OddsRedirector $redirect)
    {
        return $redirect->to('markets');
    }

    public function selections(OddsRedirector $redirect)
    {
        return $redirect->to('selections');
    }

}
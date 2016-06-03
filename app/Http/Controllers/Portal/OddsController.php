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

    public function fixture(OddsRedirector $redirect, $id)
    {
        return $redirect->to('fixture', $id);
    }

    public function sports(OddsRedirector $redirect)
    {
        return $redirect->to('sports');
    }

    public function sport(OddsRedirector $redirect, $id)
    {
        return $redirect->to('sport', $id);
    }

    public function regions(OddsRedirector $redirect)
    {
        return $redirect->to('regions');
    }

    public function region(OddsRedirector $redirect)
    {
        return $redirect->to('region');
    }

    public function competitions(OddsRedirector $redirect)
    {
        return $redirect->to('competitions');
    }

    public function competition(OddsRedirector $redirect)
    {
        return $redirect->to('competition');
    }
    public function markets(OddsRedirector $redirect)
    {
        return $redirect->to('markets');
    }

    public function market(OddsRedirector $redirect)
    {
        return $redirect->to('market');
    }

    public function selections(OddsRedirector $redirect)
    {
        return $redirect->to('selections');
    }

    public function selection(OddsRedirector $redirect)
    {
        return $redirect->to('selection');
    }

}
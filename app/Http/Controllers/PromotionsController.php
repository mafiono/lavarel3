<?php

namespace App\Http\Controllers;

use App\Models\Promotion;

class PromotionsController extends Controller
{
    public function index()
    {
        return Promotion::whereActive(true)
            ->whereType('sports')
            ->get();
    }
}
<?php

namespace App\Http\Controllers\Portal;

use App\User;
use Auth;
use Illuminate\Routing\Controller;

class TesterController extends Controller
{
    public function index($type = 'basic', $id = 313)
    {
        if (!Auth::check()) {
            $user = User::findById($id);
        } else {
            $user = Auth::user();
        }
        $title = 'THIS IS A TITLE';
        $url = 'www.casinoportugal.pt';
        $button = 'CONFIRMAR';
        $value = '20,00';
        $nr = '00001';

        return view('emails.types.' . $type, compact('title', 'user', 'url', 'button', 'value', 'nr'));
    }
}


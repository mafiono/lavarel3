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
        $vars = [
            'user' => $user,
            'title' => 'THIS IS A TITLE',
            'url' => 'www.casinoportugal.pt',
            'host' => 'https://www.casinoportugal.pt',
            'button' => 'CONFIRMAR',
            'value' => '20,00',
            'nr' => '00001',
            'exclusion' => $this->array_random(['reflection_period', 'undetermined_period', 'other']),
            'time' => '5',
            'motive' => 'Uso indevido!'
        ];
//        dd($vars);
        return view('emails.types.' . $type, $vars);
    }

    private function array_random($arr, $num = 1) {
        shuffle($arr);

        $r = array();
        for ($i = 0; $i < $num; $i++) {
            $r[] = $arr[$i];
        }
        return $num == 1 ? $r[0] : $r;
    }
}


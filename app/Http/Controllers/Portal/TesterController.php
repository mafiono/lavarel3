<?php

namespace App\Http\Controllers\Portal;

use App\Lib\Mail\SendMail;
use App\User;
use Auth;
use Illuminate\Routing\Controller;
use ReflectionClass;
use Request;

class TesterController extends Controller
{
    public function listViews($id = 313) {
        $r = new ReflectionClass(SendMail::class);
        $views = $r->getStaticProperties();
        $url = Request::getUriForPath('/tester');
        foreach ($views as $key => $view) {
            echo "<br><a href='$url/$id/$view'>$key</a> => $view";
        }
    }

    public function index($id = 313, $type = 'basic')
    {
        if (!Auth::check()) {
            $user = User::findById($id);
        } else {
            $user = Auth::user();
        }
        $url = Request::getUriForPath('/tester/') . $id;
        $vars = [
            'user' => $user,
            'name' => $user->username,
            'email' => $user->profile->email,
            'title' => 'THIS IS A TITLE',
            'url' => Request::getUriForPath('/'),
            'host' => Request::getUriForPath('/'),
            'button' => 'CONFIRMAR',
            'value' => '20,00',
            'nr' => '00001',
            'exclusion' => $this->array_random(['reflection_period', 'undetermined_period', 'other']),
            'time' => '5',
            'motive' => 'Uso indevido!',
            'debug' => "<br><a href='$url'>VOLTAR</a>",
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


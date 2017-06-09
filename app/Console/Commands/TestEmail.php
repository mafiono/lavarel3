<?php

namespace App\Console\Commands;

use App\Lib\IdentityVerifier\PedidoVerificacaoTPType;
use App\Lib\IdentityVerifier\VerificacaoIdentidade;
use App\User;
use Exception;
use Illuminate\Console\Command;
use Log;
use Mail;
use Request;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify_email {type} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify Email';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $type = $this->argument('type');

        if (empty($type)) $type = 'basic';
        $user = User::findById($id);


        $vars = [
            'user' => $user,
            'name' => $user->username,
            'email' => $user->profile->email,
            'title' => 'THIS IS A TITLE',
            'url' => 'https://www.casinoportugal.pt',
            'host' => 'https://www.casinoportugal.pt',
            'button' => 'CONFIRMAR',
            'value' => '20,00',
            'nr' => '00001',
            'exclusion' => $this->array_random(['reflection_period', 'undetermined_period', 'other']),
            'time' => '5',
            'motive' => 'Uso indevido!'
        ];

        try {
            Mail::send('emails.types.' . $type, $vars,
                function ($m) use ($vars) {
                    $m->to($vars['email'], $vars['name'])->subject($vars['title']);
                });
            $this->line('Success Sending Mail!');
        } catch (Exception $e) {
            $this->error('Error sending mail: ' . $e->getMessage());
            var_dump($e->getTraceAsString());
        }
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

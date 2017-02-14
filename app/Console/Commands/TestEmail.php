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
    protected $signature = 'verify_email {id}';

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
        $user = User::findById($id);

        $token = $user->profile->email_token;

        $data = [
            'email' => $user->profile->email,
            'name' => $user->profile->name,
        ];
        try {
            $url = Request::getHost() . '/confirmar_email?email=' . $user->profile->email . '&token=' . $token;

            Mail::send('portal.sign_up.emails.signup', ['data' => $data, 'token' => $token, 'url' => $url],
                function ($m) use ($data) {
                    $m->to($data['email'], $data['name'])->subject('CasinoPortugal - Registo de Utilizador!');
                });
            $this->line('Success Sending Mail!');
        } catch (Exception $e) {
            $this->error('Error sending mail: ' . $e->getMessage());
            var_dump($e->getTraceAsString());
        }
    }
}

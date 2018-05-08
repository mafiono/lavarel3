<?php

namespace App\Console\Commands;

use App\Lib\IdentityVerifier\PedidoVerificacaoTPType;
use App\Lib\IdentityVerifier\VerificacaoIdentidade;
use App\UserProfile;
use Illuminate\Console\Command;
use Log;

class TestOldAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:old_accounts {cc} {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify Identity using Webservice';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cc = $this->argument('cc');
        $userId = $this->argument('userId');

        $account = UserProfile::getOldAccount($cc, $userId);
        dd($account);
    }
}

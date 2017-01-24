<?php

namespace App\Console\Commands;

use App\Lib\IdentityVerifier\PedidoVerificacaoTPType;
use App\Lib\IdentityVerifier\VerificacaoIdentidade;
use Illuminate\Console\Command;
use Log;

class TestIdentityVerifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify_identity {cc} {nif} {date} {name*}';

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
        $name = implode(' ', $this->argument('name'));
        $cc = $this->argument('cc');
        $nif = $this->argument('nif');
        $date = $this->argument('date');

        $ws = new VerificacaoIdentidade(['exceptions' => true, 'trace' => 1]);
        try {
            /**
             * 0 - BI (ID CARD)
             * 1 - CARTAO_CIDADAO (CITIZEN CARD)
             * 2 - PASSAPORTE (PASSPORT)
             * 3 - NUMERO IDENTIFIC FISCAL (TAX IDENTIFICATION NUMBER)
             * 4 - OUTRO (OTHER)
             */
            $tipo = 1;

            $part = new PedidoVerificacaoTPType(config('app.srij_company_code'), $name, $cc, $tipo, $date, $nif);
            $identity = $ws->verificacaoidentidade($part);
            Log::info('VIdentidade', compact('name', 'cc', 'tipo', 'date', 'nif', 'identity'));

            $this->comment($identity->Sucesso . ':' . $identity->Valido. '>' . $identity->CodigoErro . ': ' . $identity->MensagemErro. ' > ' . $identity->DetalheErro);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }

        echo "====== REQUEST HEADERS =====" . PHP_EOL;
        var_dump($ws->__getLastRequestHeaders());
        echo "========= REQUEST ==========" . PHP_EOL;
        var_dump($ws->__getLastRequest());
        echo "========= RESPONSE =========" . PHP_EOL;
        var_dump($ws->__getLastResponse());
        echo "========= END ==============" . PHP_EOL;
    }
}

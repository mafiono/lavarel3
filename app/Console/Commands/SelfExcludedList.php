<?php

namespace App\Console\Commands;

use App\Lib\SelfExclusion\CidadaoExcluidoType;
use App\Lib\SelfExclusion\ListaExcluidos;
use App\Lib\SelfExclusion\PedidoListaExcluidosType;
use App\ListSelfExclusion;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SelfExcludedList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'self-excluded-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all Self-Excluded Users from SRIJ';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api = null;
        try {
            ListSelfExclusion::query()->update([
                'changed' => 0
            ]);
            $api = new ListaExcluidos(['exceptions' => true, 'trace' => 1]);
            $pedido = new PedidoListaExcluidosType(config('app.srij_company_code'));
            $result = $api->getlistaexcluidos($pedido);

            $this->line("Success: ". $result->Sucesso);
            $this->line("Msg Erro: ". $result->MensagemErro);
            $subList = $result->ListaCidadaoExcludo->CidadaoExcluido;
            foreach ($subList as $item){
                /* @var CidadaoExcluidoType $item */
                /*
                 * IdTipoCid
                 * 0 BI, 1 CARTAO CIDADAO, 2 PASSAPORTE, 3 NUMERO IDENTIFIC FISCAL, 4 OUTRO. NOT NULL
                 * */
                print_r($item);

                $newItem = ListSelfExclusion::query()
                    ->where('document_number', '=', $item->IdCidadao)
                    ->where('doc_type_id', '=', $item->IdTipoCid)
                    ->first() ?: new ListSelfExclusion();
                $newItem->document_number = $item->IdCidadao;
                $newItem->doc_type_id = $item->IdTipoCid;
                $newItem->nation_id = $item->IdNacao;
                $newItem->document_type_id = 'cartao_cidadao';
                $newItem->start_date = $item->DataInicio;
                $newItem->end_date = $item->DataFim;
                $newItem->confirmed = $item->Confirmado === 'S' ? 1 : 0;
                $newItem->changed = 1;

                $newItem->save();
            }

            ListSelfExclusion::query()
                ->where('changed', '=', 0)
                ->delete();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }

        echo "====== REQUEST HEADERS =====" . PHP_EOL;
        var_dump($api->__getLastRequestHeaders());
        echo "========= REQUEST ==========" . PHP_EOL;
        var_dump($api->__getLastRequest());
        echo "========= RESPONSE =========" . PHP_EOL;
    }
}

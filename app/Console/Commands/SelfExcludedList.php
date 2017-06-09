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
            ListSelfExclusion::query()
                ->where('origin', '=', 'srij')
                ->update([
                    'changed' => 0
                ]);
            $code = (string)(int)config('app.srij_company_code');
            $api = new ListaExcluidos(['exceptions' => true, 'trace' => 1]);
            $pedido = new PedidoListaExcluidosType($code);
            $result = $api->getlistaexcluidos($pedido);

            // ignorar se não tiver um resultado válido.
            if (!$result->Sucesso) return;

            $this->line("Success: ". $result->Sucesso);
            $this->line("Msg Erro: ". $result->MensagemErro);
            $subList = (array) $result->ListaCidadaoExcludo->CidadaoExcluido;
            if ($subList !== null) {
                $row = 1;
                $types = [
                    '0' => 'bi', // BI (ID CARD)
                    '1' => 'cartao_cidadao', // CARTAO_CIDADAO (CITIZEN CARD)
                    '2' => 'passaporte', // PASSAPORTE (PASSPORT)
                    '3' => 'nif', // NUMERO IDENTIFIC FISCAL (TAX IDENTIFICATION NUMBER)
                    '4' => 'outro', // OUTRO (OTHER)
                ];

                foreach ($subList as $item){
                    /* @var CidadaoExcluidoType $item */
                    /*
                     * IdTipoCid
                     * 0 BI, 1 CARTAO CIDADAO, 2 PASSAPORTE, 3 NUMERO IDENTIFIC FISCAL, 4 OUTRO. NOT NULL
                     * */
                    // print_r($item);

                    $newItem = ListSelfExclusion::query()
                        ->where('document_number', '=', $item->IdCidadao)
                        ->where('doc_type_id', '=', $item->IdTipoCid)
                        ->first() ?: new ListSelfExclusion();


                    $newItem->document_number = $item->IdCidadao;
                    $newItem->doc_type_id = $item->IdTipoCid;
                    $newItem->nation_id = $item->IdNacao;
                    $newItem->document_type_id = $types[$item->IdTipoCid];
                    $newItem->start_date = Carbon::parse($item->DataInicio, 'UTC');
                    $newItem->end_date = $item->DataFim !== null ? Carbon::parse($item->DataInicio, 'UTC') : null;
                    $newItem->confirmed = $item->Confirmado === 'S' ? 1 : 0;
                    $newItem->origin ='srij';
                    $newItem->changed = 1;

                    $newItem->save();

                    $this->line($row++ . " Processed nr {$newItem->document_number}");
                }
            }

            $total = ListSelfExclusion::query()
                ->where('origin', '=', 'srij')
                ->where('changed', '=', 0)
                ->delete();
            $this->line("Deleted $total");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());

            echo "====== REQUEST HEADERS =====" . PHP_EOL;
            var_dump($api->__getLastRequestHeaders());
            echo "========= REQUEST ==========" . PHP_EOL;
            var_dump($api->__getLastRequest());
            echo "========= RESPONSE =========" . PHP_EOL;
            var_dump($api->__getLastResponse());
            echo "========= END ==============" . PHP_EOL;
        }
    }
}

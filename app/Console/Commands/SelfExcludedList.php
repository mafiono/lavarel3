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
        ListSelfExclusion::query()->update([
            'changed' => 0
        ]);
        $api = new ListaExcluidos();
        $pedido = new PedidoListaExcluidosType();
        $pedido->setCodEntidadeExploradora(env('SRIJ_COMPANY_CODE', ''));
        $result = $api->getlistaexcluidos($pedido);

        $this->line("Success: ". $result->getSucesso());
        $this->line("Msg Erro: ". $result->getMensagemErro());
        $subList = $result->getListaCidadaoExcludo()->getCidadaoExcluido();
        foreach ($subList as $item){
            /* @var CidadaoExcluidoType $item */
            /*
             * IdTipoCid
             * 0 BI, 1 CARTAO CIDADAO, 2 PASSAPORTE, 3 NUMERO IDENTIFIC FISCAL, 4 OUTRO. NOT NULL
             * */
            print_r($item);

            $newItem = ListSelfExclusion::query()
                ->where('document_number', '=', $item->getIdCidadao())
                ->where('doc_type_id', '=', $item->getIdTipoCid())
                ->first() ?: new ListSelfExclusion();
            $newItem->document_number = $item->getIdCidadao();
            $newItem->doc_type_id = $item->getIdTipoCid();
            $newItem->nation_id = $item->getIdNacao();
            $newItem->document_type_id = 'cartao_cidadao';
            $newItem->start_date = $item->getDataInicio();
            $newItem->end_date = $item->getDataFim();
            $newItem->confirmed = $item->getConfirmado() === 'S' ? 1 : 0;
            $newItem->changed = 1;

            $newItem->save();

        }

        ListSelfExclusion::query()
            ->where('changed', '=', 0)
            ->delete();
    }
}

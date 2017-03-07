<?php

namespace App\Lib\NotifyExclusion;
use App\ListSelfExclusion;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * WSDL File for NotificacaoPedidoExclusao_Service
 */
class NotificacaoPedidoExclusaoServer
{
    public function __construct()
    {
        /**
         * Configure the database and boot Eloquent
         */
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        // set timezone for timestamps etc
        // date_default_timezone_set('UTC');
    }

    /**
     * @param NotificacaoPedidoExclusaoType $part
     * @return RespostaNotificacaoPedidoExclusaoType
     */
    public function sendExcluido($part)
    {
        $id = $part->IdCidadao;
        $typeId = $part->IdTipoCid;
        $type = 'cartao_cidadao';
        // 0 BI, 1 CARTAO CIDADAO, 2 PASSAPORTE, 3 NUMERO IDENTIFIC FISCAL, 4 OUTRO.
        switch ($typeId){
            case '0': $type = 'bi'; break;
            case '1': $type = 'cartao_cidadao'; break;
            case '2': $type = 'passaporte'; break;
            case '3': $type = 'nif'; break;
            case '4': $type = 'outro'; break;
        }
        try {
            $item = ListSelfExclusion::query()
                ->where('document_number', '=', $id)
                ->where('doc_type_id', '=', $typeId)
                ->first() ?: new ListSelfExclusion();
            $item->doc_type_id = $typeId;
            $item->document_number = $id;
            $item->document_type_id = $type;
            $item->nation_id = $part->IdNacao;
            $item->confirmed = $part->Confirmado == 'S' ? 1 : 0;
            $item->start_date = $part->DataInicio;
            $item->end_date = $part->DataFim;

            $item->save();

            $response = new RespostaNotificacaoPedidoExclusaoType(true, "");
        } catch (Exception $ex) {
            $response = new RespostaNotificacaoPedidoExclusaoType(false, $ex->getMessage());
        }

        return $response;
    }

}

<?php

namespace App\Lib\NotifyExclusion;

class NotificacaoPedidoExclusaoType
{
    /**
     * @var string20 $IdCidadao
     */
    public $IdCidadao = null;

    /**
     * @var int1 $IdTipoCid
     */
    public $IdTipoCid = null;

    /**
     * @var string2 $IdNacao
     */
    public $IdNacao = null;

    /**
     * @var date $DataInicio
     */
    public $DataInicio = null;

    /**
     * @var date $DataFim
     */
    public $DataFim = null;

    /**
     * @var Confirmado $Confirmado
     */
    public $Confirmado = null;

    /**
     * @param string20 $IdCidadao
     * @param int1 $IdTipoCid
     * @param string2 $IdNacao
     * @param date $DataInicio
     * @param date $DataFim
     * @param Confirmado $Confirmado
     */
    public function __construct($IdCidadao, $IdTipoCid, $IdNacao, $DataInicio, $DataFim, $Confirmado)
    {
      $this->IdCidadao = $IdCidadao;
      $this->IdTipoCid = $IdTipoCid;
      $this->IdNacao = $IdNacao;
      $this->DataInicio = $DataInicio;
      $this->DataFim = $DataFim;
      $this->Confirmado = $Confirmado;
    }
}

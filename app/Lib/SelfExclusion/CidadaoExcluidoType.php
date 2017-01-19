<?php

namespace App\Lib\SelfExclusion;

class CidadaoExcluidoType
{

    /**
     * @var int1 $IdTipoCid
     * @access public
     */
    public $IdTipoCid = null;

    /**
     * @var string20 $IdCidadao
     * @access public
     */
    public $IdCidadao = null;

    /**
     * @var string2 $IdNacao
     * @access public
     */
    public $IdNacao = null;

    /**
     * @var date $DataInicio
     * @access public
     */
    public $DataInicio = null;

    /**
     * @var date $DataFim
     * @access public
     */
    public $DataFim = null;

    /**
     * @var Confirmado $Confirmado
     * @access public
     */
    public $Confirmado = null;

    /**
     * @param int1 $IdTipoCid
     * @param string20 $IdCidadao
     * @param string2 $IdNacao
     * @param date $DataInicio
     * @param date $DataFim
     * @param Confirmado $Confirmado
     * @access public
     */
    public function __construct($IdTipoCid, $IdCidadao, $IdNacao, $DataInicio, $DataFim, $Confirmado)
    {
      $this->IdTipoCid = $IdTipoCid;
      $this->IdCidadao = $IdCidadao;
      $this->IdNacao = $IdNacao;
      $this->DataInicio = $DataInicio;
      $this->DataFim = $DataFim;
      $this->Confirmado = $Confirmado;
    }

}

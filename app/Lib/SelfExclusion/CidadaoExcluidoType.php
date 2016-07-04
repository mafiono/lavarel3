<?php

namespace App\Lib\SelfExclusion;

class CidadaoExcluidoType
{

    /**
     * @var int $IdTipoCid
     */
    protected $IdTipoCid = null;

    /**
     * @var string $IdCidadao
     */
    protected $IdCidadao = null;

    /**
     * @var string $IdNacao
     */
    protected $IdNacao = null;

    /**
     * @var date $DataInicio
     */
    protected $DataInicio = null;

    /**
     * @var date $DataFim
     */
    protected $DataFim = null;

    /**
     * @var Confirmado $Confirmado
     */
    protected $Confirmado = null;

    /**
     * @param int $IdTipoCid
     * @param date $DataInicio
     * @param date $DataFim
     * @param Confirmado $Confirmado
     */
    public function __construct($IdTipoCid, $DataInicio, $DataFim, $Confirmado)
    {
        $this->IdTipoCid = $IdTipoCid;
        $this->DataInicio = $DataInicio;
        $this->DataFim = $DataFim;
        $this->Confirmado = $Confirmado;
    }

    /**
     * @return int
     */
    public function getIdTipoCid()
    {
        return $this->IdTipoCid;
    }

    /**
     * @param int $IdTipoCid
     * @return \App\Lib\SelfExclusion\CidadaoExcluidoType
     */
    public function setIdTipoCid($IdTipoCid)
    {
        $this->IdTipoCid = $IdTipoCid;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdCidadao()
    {
        return $this->IdCidadao;
    }

    /**
     * @param string $IdCidadao
     * @return \App\Lib\SelfExclusion\CidadaoExcluidoType
     */
    public function setIdCidadao($IdCidadao)
    {
        $this->IdCidadao = $IdCidadao;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdNacao()
    {
        return $this->IdNacao;
    }

    /**
     * @param string $IdNacao
     * @return \App\Lib\SelfExclusion\CidadaoExcluidoType
     */
    public function setIdNacao($IdNacao)
    {
        $this->IdNacao = $IdNacao;
        return $this;
    }

    /**
     * @return date
     */
    public function getDataInicio()
    {
        return $this->DataInicio;
    }

    /**
     * @param date $DataInicio
     * @return \App\Lib\SelfExclusion\CidadaoExcluidoType
     */
    public function setDataInicio($DataInicio)
    {
        $this->DataInicio = $DataInicio;
        return $this;
    }

    /**
     * @return date
     */
    public function getDataFim()
    {
        return $this->DataFim;
    }

    /**
     * @param date $DataFim
     * @return \App\Lib\SelfExclusion\CidadaoExcluidoType
     */
    public function setDataFim($DataFim)
    {
        $this->DataFim = $DataFim;
        return $this;
    }

    /**
     * @return Confirmado
     */
    public function getConfirmado()
    {
        return $this->Confirmado;
    }

    /**
     * @param Confirmado $Confirmado
     * @return \App\Lib\SelfExclusion\CidadaoExcluidoType
     */
    public function setConfirmado($Confirmado)
    {
        $this->Confirmado = $Confirmado;
        return $this;
    }

}

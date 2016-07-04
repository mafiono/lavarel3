<?php

namespace App\Lib\SelfExclusion;

class ListaCidadaoExcluidoType
{

    /**
     * @var boolean $Sucesso
     */
    protected $Sucesso = null;

    /**
     * @var ListaCidadaoExcludo $ListaCidadaoExcludo
     */
    protected $ListaCidadaoExcludo = null;

    /**
     * @var string $MensagemErro
     */
    protected $MensagemErro = null;

    /**
     * @param boolean $Sucesso
     */
    public function __construct($Sucesso)
    {
        $this->Sucesso = $Sucesso;
    }

    /**
     * @return boolean
     */
    public function getSucesso()
    {
        return $this->Sucesso;
    }

    /**
     * @param boolean $Sucesso
     * @return \App\Lib\SelfExclusion\ListaCidadaoExcluidoType
     */
    public function setSucesso($Sucesso)
    {
        $this->Sucesso = $Sucesso;
        return $this;
    }

    /**
     * @return ListaCidadaoExcludo
     */
    public function getListaCidadaoExcludo()
    {
        return $this->ListaCidadaoExcludo;
    }

    /**
     * @param ListaCidadaoExcludo $ListaCidadaoExcludo
     * @return \App\Lib\SelfExclusion\ListaCidadaoExcluidoType
     */
    public function setListaCidadaoExcludo($ListaCidadaoExcludo)
    {
        $this->ListaCidadaoExcludo = $ListaCidadaoExcludo;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagemErro()
    {
        return $this->MensagemErro;
    }

    /**
     * @param string $MensagemErro
     * @return \App\Lib\SelfExclusion\ListaCidadaoExcluidoType
     */
    public function setMensagemErro($MensagemErro)
    {
        $this->MensagemErro = $MensagemErro;
        return $this;
    }

}

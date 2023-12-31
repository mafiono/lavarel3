<?php

namespace App\Lib\SelfExclusion;

class ListaCidadaoExcluidoType
{

    /**
     * @var boolean $Sucesso
     * @access public
     */
    public $Sucesso = null;

    /**
     * @var ListaCidadaoExcludo $ListaCidadaoExcludo
     * @access public
     */
    public $ListaCidadaoExcludo = null;

    /**
     * @var string10 $CodigoErro
     * @access public
     */
    public $CodigoErro = null;

    /**
     * @var string $MensagemErro
     * @access public
     */
    public $MensagemErro = null;

    /**
     * @var string $DetalheErro
     * @access public
     */
    public $DetalheErro = null;

    /**
     * @param boolean $Sucesso
     * @param ListaCidadaoExcludo $ListaCidadaoExcludo
     * @param string10 $CodigoErro
     * @param string $MensagemErro
     * @param string $DetalheErro
     * @access public
     */
    public function __construct($Sucesso, $ListaCidadaoExcludo, $CodigoErro, $MensagemErro, $DetalheErro)
    {
      $this->Sucesso = $Sucesso;
      $this->ListaCidadaoExcludo = $ListaCidadaoExcludo;
      $this->CodigoErro = $CodigoErro;
      $this->MensagemErro = $MensagemErro;
      $this->DetalheErro = $DetalheErro;
    }

}

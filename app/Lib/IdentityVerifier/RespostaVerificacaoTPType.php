<?php

namespace App\Lib\IdentityVerifier;

class RespostaVerificacaoTPType
{

    /**
     * @var boolean $Sucesso
     * @access public
     */
    public $Sucesso = null;

    /**
     * @var stringSN $Valido
     * @access public
     */
    public $Valido = null;

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
     * @param stringSN $Valido
     * @param string10 $CodigoErro
     * @param string $MensagemErro
     * @param string $DetalheErro
     * @access public
     */
    public function __construct($Sucesso, $Valido, $CodigoErro, $MensagemErro, $DetalheErro)
    {
      $this->Sucesso = $Sucesso;
      $this->Valido = $Valido;
      $this->CodigoErro = $CodigoErro;
      $this->MensagemErro = $MensagemErro;
      $this->DetalheErro = $DetalheErro;
    }

}

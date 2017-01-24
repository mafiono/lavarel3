<?php

namespace App\Lib\IdentityVerifier;

class RespostaVerificacaoJogadorRegistadoType
{

    /**
     * @var boolean $Sucesso
     * @access public
     */
    public $Sucesso = null;

    /**
     * @var stringSN $JogadorValido
     * @access public
     */
    public $JogadorValido = null;

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
     * @param stringSN $JogadorValido
     * @param string $MensagemErro
     * @param string $DetalheErro
     * @access public
     */
    public function __construct($Sucesso, $JogadorValido, $MensagemErro, $DetalheErro)
    {
      $this->Sucesso = $Sucesso;
      $this->JogadorValido = $JogadorValido;
      $this->MensagemErro = $MensagemErro;
      $this->DetalheErro = $DetalheErro;
    }

}

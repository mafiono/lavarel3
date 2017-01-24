<?php

class PedidoVerificacaoJogadorRegistadoType
{

    /**
     * @var string $NumeroIdentificacaoJogador
     * @access public
     */
    public $NumeroIdentificacaoJogador = null;

    /**
     * @var string $TipoIdentificacaoJogador
     * @access public
     */
    public $TipoIdentificacaoJogador = null;

    /**
     * @var int $NifJogador
     * @access public
     */
    public $NifJogador = null;

    /**
     * @param string $NumeroIdentificacaoJogador
     * @param string $TipoIdentificacaoJogador
     * @param int $NifJogador
     * @access public
     */
    public function __construct($NumeroIdentificacaoJogador, $TipoIdentificacaoJogador, $NifJogador)
    {
      $this->NumeroIdentificacaoJogador = $NumeroIdentificacaoJogador;
      $this->TipoIdentificacaoJogador = $TipoIdentificacaoJogador;
      $this->NifJogador = $NifJogador;
    }

}

<?php

namespace App\Lib\IdentityVerifier;

class PedidoVerificacaoTPType
{

    /**
     * @var string3 $CodEntidadeExploradora
     * @access public
     */
    public $CodEntidadeExploradora = null;

    /**
     * @var string $Nome
     * @access public
     */
    public $Nome = null;

    /**
     * @var string $NumeroIdentificacao
     * @access public
     */
    public $NumeroIdentificacao = null;

    /**
     * @var int $TipoIdentificacao
     * @access public
     */
    public $TipoIdentificacao = null;

    /**
     * @var date $DataNascimento
     * @access public
     */
    public $DataNascimento = null;

    /**
     * @var int $Nif
     * @access public
     */
    public $Nif = null;

    /**
     * @param string3 $CodEntidadeExploradora
     * @param string $Nome
     * @param string $NumeroIdentificacao
     * @param int $TipoIdentificacao
     * @param date $DataNascimento
     * @param int $Nif
     * @access public
     */
    public function __construct($CodEntidadeExploradora, $Nome, $NumeroIdentificacao, $TipoIdentificacao, $DataNascimento, $Nif)
    {
      $this->CodEntidadeExploradora = $CodEntidadeExploradora;
      $this->Nome = $Nome;
      $this->NumeroIdentificacao = $NumeroIdentificacao;
      $this->TipoIdentificacao = $TipoIdentificacao;
      $this->DataNascimento = $DataNascimento;
      $this->Nif = $Nif;
    }

}

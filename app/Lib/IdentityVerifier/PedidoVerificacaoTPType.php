<?php

namespace App\Lib\IdentityVerifier;

class PedidoVerificacaoTPType
{

    /**
     * @var string $Nome
     */
    protected $Nome = null;

    /**
     * @var int $Nif
     */
    protected $Nif = null;

    /**
     * @var date $DataNascimento
     */
    protected $DataNascimento = null;

    /**
     * @param int $Nif
     * @param date $DataNascimento
     */
    public function __construct($Nif, $DataNascimento)
    {
        $this->Nif = $Nif;
        $this->DataNascimento = $DataNascimento;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->Nome;
    }

    /**
     * @param string $Nome
     * @return \App\Lib\IdentityVerifier\PedidoVerificacaoTPType
     */
    public function setNome($Nome)
    {
        $this->Nome = $Nome;
        return $this;
    }

    /**
     * @return int
     */
    public function getNif()
    {
        return $this->Nif;
    }

    /**
     * @param int $Nif
     * @return \App\Lib\IdentityVerifier\PedidoVerificacaoTPType
     */
    public function setNif($Nif)
    {
        $this->Nif = $Nif;
        return $this;
    }

    /**
     * @return date
     */
    public function getDataNascimento()
    {
        return $this->DataNascimento;
    }

    /**
     * @param date $DataNascimento
     * @return \App\Lib\IdentityVerifier\PedidoVerificacaoTPType
     */
    public function setDataNascimento($DataNascimento)
    {
        $this->DataNascimento = $DataNascimento;
        return $this;
    }

}

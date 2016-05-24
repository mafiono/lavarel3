<?php

namespace App\IdentityVerifier;

class IdentityRequest
{
    /**
     * @var string $Nome
     * @access public
     */
    public $Nome = null;

    /**
     * @var int $Nif
     * @access public
     */
    public $Nif = null;

    /**
     * @var date $DataNascimento
     * @access public
     */
    public $DataNascimento = null;

    /**
     * @param string $Nome
     * @param int $Nif
     * @param date $DataNascimento
     * @access public
     */
    public function __construct($Nome, $Nif, $DataNascimento)
    {
        $this->Nome = $Nome;
        $this->Nif = $Nif;
        $this->DataNascimento = $DataNascimento;
    }
}
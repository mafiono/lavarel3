<?php

namespace App\Lib\IdentityVerifier;

class PedidoVerificacaoType
{

    /**
     * @var int $Nif
     * @access public
     */
    public $Nif = null;

    /**
     * @param int $Nif
     * @access public
     */
    public function __construct($Nif)
    {
      $this->Nif = $Nif;
    }

}

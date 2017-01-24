<?php

namespace App\Lib\SelfExclusion;

class PedidoListaExcluidosType
{

    /**
     * @var string3 $CodEntidadeExploradora
     * @access public
     */
    public $CodEntidadeExploradora = null;

    /**
     * @param string3 $CodEntidadeExploradora
     * @access public
     */
    public function __construct($CodEntidadeExploradora)
    {
      $this->CodEntidadeExploradora = $CodEntidadeExploradora;
    }

}

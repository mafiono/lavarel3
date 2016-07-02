<?php

namespace App\Lib\SelfExclusion;

class PedidoListaExcluidosType
{

    /**
     * @var string $CodEntidadeExploradora
     */
    protected $CodEntidadeExploradora = null;


    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getCodEntidadeExploradora()
    {
        return $this->CodEntidadeExploradora;
    }

    /**
     * @param string $CodEntidadeExploradora
     * @return \App\Lib\SelfExclusion\PedidoListaExcluidosType
     */
    public function setCodEntidadeExploradora($CodEntidadeExploradora)
    {
        $this->CodEntidadeExploradora = $CodEntidadeExploradora;
        return $this;
    }

}

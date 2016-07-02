<?php

namespace App\Lib\SelfExclusion;

class ListaCidadaoExcludo
{

    /**
     * @var CidadaoExcluidoType $CidadaoExcluido
     */
    protected $CidadaoExcluido = null;

    /**
     * @param CidadaoExcluidoType $CidadaoExcluido
     */
    public function __construct($CidadaoExcluido)
    {
        $this->CidadaoExcluido = $CidadaoExcluido;
    }

    /**
     * @return CidadaoExcluidoType
     */
    public function getCidadaoExcluido()
    {
        return $this->CidadaoExcluido;
    }

    /**
     * @param CidadaoExcluidoType $CidadaoExcluido
     * @return \App\Lib\SelfExclusion\ListaCidadaoExcludo
     */
    public function setCidadaoExcluido($CidadaoExcluido)
    {
        $this->CidadaoExcluido = $CidadaoExcluido;
        return $this;
    }

}

<?php

namespace App\Lib\SelfExclusion;

class ListaCidadaoExcludo
{

    /**
     * @var CidadaoExcluidoType $CidadaoExcluido
     * @access public
     */
    public $CidadaoExcluido = null;

    /**
     * @param CidadaoExcluidoType $CidadaoExcluido
     * @access public
     */
    public function __construct($CidadaoExcluido)
    {
      $this->CidadaoExcluido = $CidadaoExcluido;
    }

}

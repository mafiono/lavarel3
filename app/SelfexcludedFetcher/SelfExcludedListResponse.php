<?php

namespace App\SelfExcludedFetcher;

use App\UserSelfExclusion;

class SelfExcludedListResponse
{

    /**
     * @var boolean $Sucesso
     * @access public
     */
    public $Sucesso = null;

    /**
     * @var ListaCidadaoExcludo $ListaCidadaoExcludo
     * @access public
     */
    public $ListaCidadaoExcludo = null;

    /**
     * @var string $MensagemErro
     * @access public
     */
    public $MensagemErro = null;

    /**
     * @param boolean $Sucesso
     * @param ListaCidadaoExcludo $ListaCidadaoExcludo
     * @param string $MensagemErro
     * @access public
     */
    public function __construct($Sucesso, $ListaCidadaoExcludo, $MensagemErro)
    {
        $this->Sucesso = $Sucesso;
        $this->ListaCidadaoExcludo = $ListaCidadaoExcludo;
        $this->MensagemErro = $MensagemErro;
    }

}

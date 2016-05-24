<?php

namespace App\IdentityVerifier;

class IdentityResponse
{
    /**
     * @var boolean $Sucesso
     * @access public
     */
    public $Sucesso = null;

    /**
     * @var stringSN $NomeValido
     * @access public
     */
    public $NomeValido = null;

    /**
     * @var string $NomeCompleto
     * @access public
     */
    public $NomeCompleto = null;

    /**
     * @var stringSN $NifValido
     * @access public
     */
    public $NifValido = null;

    /**
     * @var stringSN $DataNascimentoValida
     * @access public
     */
    public $DataNascimentoValida = null;

    /**
     * @var stringSN $MaiorDeIdade
     * @access public
     */
    public $MaiorDeIdade = null;

    /**
     * @var stringSN $Falecido
     * @access public
     */
    public $Falecido = null;

    /**
     * @var string $MensagemErro
     * @access public
     */
    public $MensagemErro = null;

    /**
     * @param boolean $Sucesso
     * @param stringSN $NomeValido
     * @param string $NomeCompleto
     * @param stringSN $NifValido
     * @param stringSN $DataNascimentoValida
     * @param stringSN $MaiorDeIdade
     * @param stringSN $Falecido
     * @param string $MensagemErro
     * @access public
     */
    public function __construct($Sucesso, $NomeValido, $NomeCompleto, $NifValido, $DataNascimentoValida, $MaiorDeIdade, $Falecido, $MensagemErro)
    {
        $this->Sucesso = $Sucesso;
        $this->NomeValido = $NomeValido;
        $this->NomeCompleto = $NomeCompleto;
        $this->NifValido = $NifValido;
        $this->DataNascimentoValida = $DataNascimentoValida;
        $this->MaiorDeIdade = $MaiorDeIdade;
        $this->Falecido = $Falecido;
        $this->MensagemErro = $MensagemErro;
    }
}

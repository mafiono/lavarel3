<?php

namespace App\Lib\IdentityVerifier;

class RespostaVerificacaoTPType
{

    /**
     * @var boolean $Sucesso
     */
    protected $Sucesso = null;

    /**
     * @var string $NomeCompleto
     */
    protected $NomeCompleto = null;

    /**
     * @var stringSN $NomeValido
     */
    protected $NomeValido = null;

    /**
     * @var stringSN $NifValido
     */
    protected $NifValido = null;

    /**
     * @var stringSN $MaiorDeIdade
     */
    protected $MaiorDeIdade = null;

    /**
     * @var stringSN $DataNascimentoValida
     */
    protected $DataNascimentoValida = null;

    /**
     * @var stringSN $Falecido
     */
    protected $Falecido = null;

    /**
     * @var string $MensagemErro
     */
    protected $MensagemErro = null;

    /**
     * @param boolean $Sucesso
     * @param stringSN $NomeValido
     * @param stringSN $NifValido
     * @param stringSN $MaiorDeIdade
     * @param stringSN $DataNascimentoValida
     */
    public function __construct($Sucesso, $NomeValido, $NifValido, $MaiorDeIdade, $DataNascimentoValida)
    {
        $this->Sucesso = $Sucesso;
        $this->NomeValido = $NomeValido;
        $this->NifValido = $NifValido;
        $this->MaiorDeIdade = $MaiorDeIdade;
        $this->DataNascimentoValida = $DataNascimentoValida;
    }

    /**
     * @return boolean
     */
    public function getSucesso()
    {
        return $this->Sucesso;
    }

    /**
     * @param boolean $Sucesso
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setSucesso($Sucesso)
    {
        $this->Sucesso = $Sucesso;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomeCompleto()
    {
        return $this->NomeCompleto;
    }

    /**
     * @param string $NomeCompleto
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setNomeCompleto($NomeCompleto)
    {
        $this->NomeCompleto = $NomeCompleto;
        return $this;
    }

    /**
     * @return stringSN
     */
    public function getNomeValido()
    {
        return $this->NomeValido;
    }

    /**
     * @param stringSN $NomeValido
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setNomeValido($NomeValido)
    {
        $this->NomeValido = $NomeValido;
        return $this;
    }

    /**
     * @return stringSN
     */
    public function getNifValido()
    {
        return $this->NifValido;
    }

    /**
     * @param stringSN $NifValido
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setNifValido($NifValido)
    {
        $this->NifValido = $NifValido;
        return $this;
    }

    /**
     * @return stringSN
     */
    public function getMaiorDeIdade()
    {
        return $this->MaiorDeIdade;
    }

    /**
     * @param stringSN $MaiorDeIdade
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setMaiorDeIdade($MaiorDeIdade)
    {
        $this->MaiorDeIdade = $MaiorDeIdade;
        return $this;
    }

    /**
     * @return stringSN
     */
    public function getDataNascimentoValida()
    {
        return $this->DataNascimentoValida;
    }

    /**
     * @param stringSN $DataNascimentoValida
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setDataNascimentoValida($DataNascimentoValida)
    {
        $this->DataNascimentoValida = $DataNascimentoValida;
        return $this;
    }

    /**
     * @return stringSN
     */
    public function getFalecido()
    {
        return $this->Falecido;
    }

    /**
     * @param stringSN $Falecido
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setFalecido($Falecido)
    {
        $this->Falecido = $Falecido;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagemErro()
    {
        return $this->MensagemErro;
    }

    /**
     * @param string $MensagemErro
     * @return \App\Lib\IdentityVerifier\RespostaVerificacaoTPType
     */
    public function setMensagemErro($MensagemErro)
    {
        $this->MensagemErro = $MensagemErro;
        return $this;
    }

}

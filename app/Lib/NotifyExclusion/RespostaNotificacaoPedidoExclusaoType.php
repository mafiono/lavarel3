<?php

namespace App\Lib\NotifyExclusion;

class RespostaNotificacaoPedidoExclusaoType
{

    /**
     * @var boolean $Sucesso
     */
    protected $Sucesso = null;

    /**
     * @var string $MensagemErro
     */
    protected $MensagemErro = null;

    /**
     * @param boolean $Sucesso
     * @param string $MensagemErro
     */
    public function __construct($Sucesso, $MensagemErro)
    {
      $this->Sucesso = $Sucesso;
      $this->MensagemErro = $MensagemErro;
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
     * @return \App\Lib\NotifyExclusion\RespostaNotificacaoPedidoExclusaoType
     */
    public function setSucesso($Sucesso)
    {
      $this->Sucesso = $Sucesso;
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
     * @return \App\Lib\NotifyExclusion\RespostaNotificacaoPedidoExclusaoType
     */
    public function setMensagemErro($MensagemErro)
    {
      $this->MensagemErro = $MensagemErro;
      return $this;
    }

}

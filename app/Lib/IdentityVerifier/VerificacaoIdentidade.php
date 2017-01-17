<?php

namespace App\Lib\IdentityVerifier;

/**
 * OSB Service
 */
class VerificacaoIdentidade extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = array(
      'PedidoVerificacaoJogadorRegistadoType' => 'App\\Lib\\IdentityVerifier\\PedidoVerificacaoJogadorRegistadoType',
      'RespostaVerificacaoJogadorRegistadoType' => 'App\\Lib\\IdentityVerifier\\RespostaVerificacaoJogadorRegistadoType',
      'PedidoVerificacaoTPType' => 'App\\Lib\\IdentityVerifier\\PedidoVerificacaoTPType',
      'PedidoVerificacaoType' => 'App\\Lib\\IdentityVerifier\\PedidoVerificacaoType',
      'RespostaVerificacaoTPType' => 'App\\Lib\\IdentityVerifier\\RespostaVerificacaoTPType',
      'RespostaVerificacaoType' => 'App\\Lib\\IdentityVerifier\\RespostaVerificacaoType');

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @access public
     */
    public function __construct(array $options = array(), $wsdl = null)
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
        $options = array_merge(array(
            'features' => 1,
        ), $options);
        if (!$wsdl) {
            $wsdl = env('SRIJ_IDENTITY');
        }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param PedidoVerificacaoTPType $part
     * @access public
     * @return RespostaVerificacaoTPType
     */
    public function verificacaoidentidade(PedidoVerificacaoTPType $part)
    {
      return $this->__soapCall('verificacaoidentidade', array($part));
    }

}

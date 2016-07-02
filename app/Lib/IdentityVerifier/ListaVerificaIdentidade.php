<?php

namespace App\Lib\IdentityVerifier;

class ListaVerificaIdentidade extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array(
        'PedidoVerificacaoTPType' => 'App\\Lib\\IdentityVerifier\\PedidoVerificacaoTPType',
        'RespostaVerificacaoTPType' => 'App\\Lib\\IdentityVerifier\\RespostaVerificacaoTPType',
    );

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
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
     * @param PedidoVerificacaoTPType $PedidoVerificacaoTP
     * @return RespostaVerificacaoTPType
     */
    public function verificacaoidentidade(PedidoVerificacaoTPType $PedidoVerificacaoTP)
    {
        return $this->__soapCall('verificacaoidentidade', array($PedidoVerificacaoTP));
    }

}

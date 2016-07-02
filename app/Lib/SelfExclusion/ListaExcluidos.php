<?php

namespace App\Lib\SelfExclusion;

class ListaExcluidos extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array(
        'PedidoListaExcluidosType' => 'App\\Lib\\SelfExclusion\\PedidoListaExcluidosType',
        'ListaCidadaoExcluidoType' => 'App\\Lib\\SelfExclusion\\ListaCidadaoExcluidoType',
        'ListaCidadaoExcludo' => 'App\\Lib\\SelfExclusion\\ListaCidadaoExcludo',
        'CidadaoExcluidoType' => 'App\\Lib\\SelfExclusion\\CidadaoExcluidoType',
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
            $wsdl = env('SRIJ_SELF_EXCLUSION');
        }
        parent::__construct($wsdl, $options);
    }

    /**
     * @param PedidoListaExcluidosType $PedidoListaExcluidos
     * @return ListaCidadaoExcluidoType
     */
    public function getlistaexcluidos(PedidoListaExcluidosType $PedidoListaExcluidos)
    {
        return $this->__soapCall('getlistaexcluidos', array($PedidoListaExcluidos));
    }

}

<?php

namespace App\SelfExcludedFetcher;

class SelfExcludedListRequest
{
    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = array(
        'ListaCidadaoExcluidoType' => '\ListaCidadaoExcluidoType',
        'ListaCidadaoExcludo' => '\ListaCidadaoExcludo',
        'CidadaoExcluidoType' => '\CidadaoExcluidoType',
        'PedidoListaExcluidosType' => '\PedidoListaExcluidosType');

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @access public
     */
    public function __construct(array $options = array(), $wsdl = 'wsdl\ListaExcluidos.wsdl')
    {
        foreach (self::$classmap as $key => $value) {
            if (!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }

        parent::__construct($wsdl, $options);
    }

    /**
     * @param PedidoListaExcluidosType $part
     * @access public
     * @return ListaCidadaoExcluidoType
     */
    public function fetchSelfExcludedList(PedidoListaExcluidosType $part)
    {
        return $this->__soapCall('getlistaexcluidos', array($part));
    }
}
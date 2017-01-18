<?php

namespace App\Lib\SelfExclusion;

/**
 * OSB Service
 */
class ListaExcluidos extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = array(
      'ListaCidadaoExcluidoType' => 'App\\Lib\\SelfExclusion\\ListaCidadaoExcluidoType',
      'ListaCidadaoExcludo' => 'App\\Lib\\SelfExclusion\\ListaCidadaoExcludo',
      'CidadaoExcluidoType' => 'App\\Lib\\SelfExclusion\\CidadaoExcluidoType',
      'PedidoListaExcluidosType' => 'App\\Lib\\SelfExclusion\\PedidoListaExcluidosType');

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
          'soap_version' => SOAP_1_2,
      ), $options);
      if (!$wsdl) {
          $wsdl = env('SRIJ_SELF_EXCLUSION');
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param PedidoListaExcluidosType $part
     * @access public
     * @return ListaCidadaoExcluidoType
     */
    public function getlistaexcluidos(PedidoListaExcluidosType $part)
    {
      return $this->__soapCall('getlistaexcluidos', array($part));
    }

}

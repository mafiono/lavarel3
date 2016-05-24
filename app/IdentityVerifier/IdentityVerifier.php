<?php

namespace App\IdentityVerifier;

class IdentityVerifier extends \SoapClient
{
    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = [
        'PedidoVerificacaoTPType' => '\App\IdentityVerifier\IdentityRequest',
        'RespostaVerificacaoTPType' => '\App\IdentityVerifier\IdentityResponse'
    ];

    /**
     * IdentityVerifier constructor.
     */
    public function __construct()
    {
        foreach (self::$classmap as $key => $value)
            if (!isset($options['classmap'][$key]))
                $options['classmap'][$key] = $value;

        parent::__construct(env('SELFEXCLUSION_SERVICE', 'http://192.168.1.94:9999/SRIJ_test/ServiceDemo.svc?singleWsdl'));
    }

    /**
     * @param IdentityRequest $request
     * @access public
     * @return IdentityResponse
     */
    public function verifyIdentity(IdentityRequest $request)
    {
        return $this->__soapCall('verificacaoidentidade', [$request]);
    }

}
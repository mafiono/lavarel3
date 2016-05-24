<?php

namespace App\SelfExcludedFetcher;

class ExcludedListRequest
{

    /**
     * @var string3 $CodEntidadeExploradora
     * @access public
     */
    public $CodEntidadeExploradora = null;

    /**
     * @param string3 $CodEntidadeExploradora
     * @access public
     */
    public function __construct($operatorCode)
    {
        $this->CodEntidadeExploradora = $operatorCode;
    }

}

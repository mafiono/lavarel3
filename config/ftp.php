<?php
return array(

    /*
	|--------------------------------------------------------------------------
	| Default FTP Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the FTP connections below you wish
	| to use as your default connection for all ftp work.
	|
	*/

    'default' => 'connection1',

    /*
    |--------------------------------------------------------------------------
    | FTP Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the FTP connections setup for your application.
    |
    */

    'connections' => array(

        'ftp_afiliados' => array(
            'host'   => 'ftp1.everymatrix.com',
            'port'  => 21,
            'username' => 'betportugal',
            'password'   => 'aZw3lSw8ztZ2g',
            'passive'   => false,
        ),
    ),
);
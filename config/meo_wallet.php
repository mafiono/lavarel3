<?php
return array(
    // set your meo wallet credential
    'sandbox_api_token' => '7c8607b2928ba866c7494516a14dc544b7b51574',
    'production_api_token' => '8e988eba4d83313a076cf456d32669eac84fc4db',

    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'production'
         */
        'mode' => env('PAYMENTS_MEO_WALLET', 'production'),
 
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,
 
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
 
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/meu_wallet.log',
 
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
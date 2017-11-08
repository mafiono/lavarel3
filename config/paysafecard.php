<?php
return array(
    // set your meo wallet credential
    'live_privateKey' => 'XXX', // Private Key (DON'T SHARE WITH ANYONE)

    'sandbox_privateKey' => 'psc_z5tXyYCccM6VWQ68x-gJ-FVWVtAvRea', // Private Key (DON'T SHARE WITH ANYONE)
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => env('PAYMENTS_PAYSAFECARD', 'live'),
 
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
        'log.FileName' => storage_path() . '/logs/paysafecard.log',
 
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
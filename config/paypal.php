<?php
return array(
    // set your paypal credential
    'client_id' => 'AZJ-rVEHkRFxRenVMNm4GQ3PeKloFEbCP8ytyTVA78hHbM1G_USJ1jSatBJIE6F885n93gdTEXyKwM0V',
    'secret' => 'EHGnHyhPaj-2NTZ3kPHh9XE0xF87l6tN6bs664uIieWl-lgX4FLZ7jFpwti80h9Y6fRc6eqf3wlLEi1L',
 
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',
 
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
        'log.FileName' => storage_path() . '/logs/paypal.log',
 
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
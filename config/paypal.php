<?php
return array(
    // set your paypal credential
    'client_id' => 'AQ9Ux39Zpu_DggJJI4Yv9d3bYXLGddiWKnhdPPgkYEa0hn7lIo1Et6DsEz80pCA10OHsULXtHi2cnsLA',
    'secret' => 'ECYNS0sOKce1gsmFUxv25SWorpb_fwR5PQeOH4evCJ__w3yCSezAz0R6t8xCyL8F3iNAI8uJ9VLT3wJu',
 
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
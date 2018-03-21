<?php
return array(
    // set your paypal credential
    'live_client_id' => 'ARc2yxynD8rDuDps-J7ginALGrPRvu6XGbj1eAxe6h4vjD2_FmqwQlUZVHKjuKohKYl_YbGCC7uEkxEI',
    'live_secret' => 'EPhrRmAXVRTn-8WoYWt17ku6TKboXyKvVDAVWSwsX6xVdQI1k-3Jfwq4l2rrc93N8TiSkqLU766eufmD',

    'sandbox_client_id' => 'AQ9Ux39Zpu_DggJJI4Yv9d3bYXLGddiWKnhdPPgkYEa0hn7lIo1Et6DsEz80pCA10OHsULXtHi2cnsLA',
    'sandbox_secret' => 'ECYNS0sOKce1gsmFUxv25SWorpb_fwR5PQeOH4evCJ__w3yCSezAz0R6t8xCyL8F3iNAI8uJ9VLT3wJu',
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => env('PAYMENTS_PAYPAL', 'live'),
 
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
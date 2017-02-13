<?php
return array(
    // set your meo wallet credential
    'merchantId' => 'Cb5NqZ9KhfOAUGJeiPXEIQr6Ivksra5VDeWd2dwNZZWOVS4Omgy5qP90n9xcgSA', // Account ID
    'privateKey' => 'HEvOeLAdn4pcr83HeZoG7Pg2hqGaKUz5191tfmQiSSwpDvsNn74tSbDWlJzpvD2', // Private Key (DON'T SHARE WITH ANYONE)
    'publicKey' => 'SesKHNKPw6Jl8Y20tC7yVJKJe2tUbP8MTU8WePr7uqireNN3eI0Q5dti6T4jD6x',

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
        'log.FileName' => storage_path() . '/logs/switch_payments.log',
 
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
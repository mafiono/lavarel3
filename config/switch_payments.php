<?php
return array(
    // set your meo wallet credential
    'live_merchantId' => 'VljEDqSKOi4scL68esqhjVGeo2f72P7kBaUyMvTXbIBEXfQuucSAUNlfIyk9wGf', // Account ID
    'live_privateKey' => 'WbNIBaGwRFqKANfgvBKDlSrGdwKU8RiMHD75qPqWdEJ9DOGL8inOpsH7T2ZqmnV', // Private Key (DON'T SHARE WITH ANYONE)
    'live_publicKey' => 'x45cZGtaIlUMGXXR2DesrNFu4z8kgwNknehsIs70mRm11QeKICfsRMmOfX6g5Cj',

    'sandbox_merchantId' => 'Cb5NqZ9KhfOAUGJeiPXEIQr6Ivksra5VDeWd2dwNZZWOVS4Omgy5qP90n9xcgSA', // Account ID
    'sandbox_privateKey' => 'HEvOeLAdn4pcr83HeZoG7Pg2hqGaKUz5191tfmQiSSwpDvsNn74tSbDWlJzpvD2', // Private Key (DON'T SHARE WITH ANYONE)
    'sandbox_publicKey' => 'SesKHNKPw6Jl8Y20tC7yVJKJe2tUbP8MTU8WePr7uqireNN3eI0Q5dti6T4jD6x',
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => env('PAYMENTS_SWITCH', 'live'),
 
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
<?php
return array(
    // set your meo wallet credential
    'merchantId' => 'VljEDqSKOi4scL68esqhjVGeo2f72P7kBaUyMvTXbIBEXfQuucSAUNlfIyk9wGf', // Account ID
    'privateKey' => 'WbNIBaGwRFqKANfgvBKDlSrGdwKU8RiMHD75qPqWdEJ9DOGL8inOpsH7T2ZqmnV', // Private Key (DON'T SHARE WITH ANYONE)
    'publicKey' => 'x45cZGtaIlUMGXXR2DesrNFu4z8kgwNknehsIs70mRm11QeKICfsRMmOfX6g5Cj',

    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => env('PAYMENTS_SWIFT', 'live'),
 
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
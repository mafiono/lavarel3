<?php
return array(
    // set your paypal credential
    //'client_id' => 'ARc2yxynD8rDuDps-J7ginALGrPRvu6XGbj1eAxe6h4vjD2_FmqwQlUZVHKjuKohKYl_YbGCC7uEkxEI',
    //'secret' => 'EPhrRmAXVRTn-8WoYWt17ku6TKboXyKvVDAVWSwsX6xVdQI1k-3Jfwq4l2rrc93N8TiSkqLU766eufmD',
    'client_id' => 'AUXJA2Eui10W3s4y2CHWacgxuSK4DiAYJyLbK0wUSn36Yyb7b3rH_Wwfhlg7BXT2jUR0dgFRFLpzWbse',
    'secret' => 'EP8zmSZla6F8Gkc8CSuF9o5EgCXQQhB3rPhOs3hIrPkJfD5U6iYkv1EjwwVlS8lFMZZomhrdkkavLEoT',

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
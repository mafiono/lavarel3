<?php

//Obtem o Ip
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

$ip = get_client_ip();
//$ip = "91.199.220.255";

$whiteList = array(
    '127.0.0.1',
    '::1'
);

if(!in_array($ip, $whiteList, true)){
    checkIp($ip);
}

function checkIp($ip) {
    //Testa a versão do ip
    if (strpos($ip, ':') === false) {
        Dotenv::load(__DIR__ . '/../');

        $aContext = [];
        if (env('CURL_PROXY', false)) {
            $aContext['http'] = [
                'proxy' => env('CURL_PROXY'),
                'request_fulluri' => true,
            ];
        }
        $cxContext = stream_context_create($aContext);

        //IpV4
        $s = file_get_contents('http://ip2c.org/?ip='.$ip, false, $cxContext);
        $country = 'local desconhecido';
        $erro = null;
        switch($s[0])
        {
            case '0':
                $erro = 'Something wrong ip: ' . $ip;
                break;
            case '1':
                $reply = explode(';',$s);
                if (isRestricted($reply[1])) {
                    $country = $reply[3];
                } else {
                    $country = null;
                }
                break;
            case '2':
                echo 'Not found in database ip: ' . $ip;
                break;
        }
        if ($country !== null) {
            include __DIR__.'/../resources/views/errors/restricted.php';
            die();
        }
    } else {
        // Can't do it for IPv6
        $country = 'local desconhecido';
        include __DIR__.'/../resources/views/errors/restricted.php';
        die();
    }
}

function isRestricted($country)
{
    return in_array($country, [
        'AF', // AFGHANISTAN
        'AL', // ALBANIA
        'DE', // GERMANY
        'DZ', // ALGERIA
        'AG', // ANTIGUA AND BARBUDA
        'BE', // BELGIUM
        'BG', // BULGARIA
        'KH', // CAMBODIA
        'CA', // CANADA
        'CN', // CHINA
        'KP', // KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF
        'KR', // KOREA, REPUBLIC OF
        'CU', // CUBA
        'DK', // DENMARK
        'EC', // ECUADOR
        'ES', // SPAIN
        'EE', // ESTONIA
        'US', // UNITED STATES
        'UM', // UNITED STATES MINOR OUTLYING ISLANDS
        'PH', // PHILIPPINES
        'FR', // FRANCE
        'GF', // FRENCH GUIANA
        'PF', // FRENCH POLYNESIA
        'TF', // FRENCH SOUTHERN TERRITORIES
        'GY', // GUYANA
        'NL', // NETHERLANDS
        'AN', // NETHERLANDS ANTILLES
        'HK', // HONG KONG
        'ID', // INDONESIA
        'IQ', // IRAQ
        'IR', // IRAN (ISLAMIC REPUBLIC OF)
        'IL', // ISRAEL
        'IT', // ITALY
        'KW', // KUWAIT
        'LA', // LAO PEOPLE'S DEMOCRATIC REPUBLIC
        'LY', // LIBYAN ARAB JAMAHIRIYA
        'MO', // MACAU
        'MY', // MALAYSIA
        'MX', // MEXICO
        'MM', // MYANMAR
        'NA', // NAMIBIA
        'NI', // NICARAGUA
        'PA', // PANAMA
        'PG', // PAPUA NEW GUINEA
        'PK', // PAKISTAN
        'RO', // ROMANIA
        'CS', // SERBIA AND MONTENEGRO
        'SG', // SINGAPORE
        'SY', // SYRIAN ARAB REPUBLIC
        'SD', // SUDAN
        'TW', // TAIWAN
        'TR', // TURKEY
        'UG', // UGANDA
        'GB', // UNITED KINGDOM
        'YE', // YEMEN
        'ZW', // ZIMBABWE
    ], true);
}

<?php
use Jaybizzle\CrawlerDetect\CrawlerDetect;

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
//$ip = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";

$whiteList = array(
    '127.0.0.1',
    '185.150.69.45',
    '109.98.91.250',
    '217.138.34.212',
    '207.154.227.124',
    '139.59.214.230',
    '138.68.76.14',
    '217.9.203.51', //for NMI casino certification
    '52.213.238.117',
    '34.249.0.249',
    '64.39.102.0',
    '94.23.75.168',
    // paysafecard
    '194.1.158.23',
    '194.1.158.37',
    '194.1.158.5',
    '194.1.158.11',
    '194.1.158.6',
    '::1'
);

if (!in_array($ip, $whiteList, true)) {
    $check = true;
    $ranges = explode('.', $ip);
    $last = (int)end($ranges);
    if (0 === strpos($ip, '64.39.102.')) {
        $check = false;
    }
    if (0 === strpos($ip, '158.255.227.')) {
        if ($last >= 48 && $last <= 63) { $check = false; }
    }
    if (0 === strpos($ip, '77.111.219.')) {
        if ($last >= 192 && $last <= 207) { $check = false; }
    }
    if (0 === strpos($ip, '46.17.59.')) {
        if ($last >= 192 && $last <= 223) { $check = false; }
    }
    if (0 === strpos($ip, '62.253.231.')) {
        if ($last >= 16 && $last <= 31) { $check = false; }
    }

    if ($check) {
        $CrawlerDetect = new CrawlerDetect;

        if (!$CrawlerDetect->isCrawler()) {
            checkIp($ip);
        }
    }
}

function checkIp($ip) {
    if ($_SERVER['HTTP_USER_AGENT'] === 'switch-events/3.0.0')
        return true;
    Dotenv::load(__DIR__ . '/../');
    //Testa a versão do ip
    if (strpos($ip, ':') === false) {
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
        $aContext = [];
        if (env('CURL_PROXY', false)) {
            $aContext['http'] = [
                'proxy' => env('CURL_PROXY'),
                'request_fulluri' => true,
            ];
        }
        $cxContext = stream_context_create($aContext);

        // Can't do it for IPv6
        $country = 'local desconhecido';

        try {
            //IpV6
            $s = file_get_contents('http://api.ip2c.info/csv/'.$ip, false, $cxContext)??'';
            $parts = explode(',', str_replace('"', '', $s));

            if ($s[0] === 'p' || count($parts) < 3) {
                $country = 'local desconhecido';
            } else {
                if (isRestricted($parts[1])) {
                    $country = $parts[3];
                } else {
                    $country = null;
                }
            }
        } catch (Exception $e) {
            $country = 'local desconhecido';
        }
        if ($country !== null) {
            include __DIR__.'/../resources/views/errors/restricted.php';
            die();
        }
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

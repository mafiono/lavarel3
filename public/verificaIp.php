<?php
include_once('geoip.php');

//Obtem o Ip
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

$ip = get_client_ip();
//$ip = "89.155.168.6";

//Testa a versão do ip
if ((strpos($ip, ":") === false)) {
    //IpV4
    $gi = geoip_open("GeoIP.dat", GEOIP_STANDARD);
    $codigo = geoip_country_code_by_addr($gi, $ip);
    $nome = geoip_country_name_by_addr($gi, $ip);
    geoip_close($gi);
} else {
    //IpV6
    $gi = geoip_open("GeoIPv6.dat", GEOIP_STANDARD);
    $codigo = geoip_country_code_by_addr_v6($gi, $ip);
    $nome = geoip_country_name_by_addr_v6($gi, $ip);
    geoip_close($gi);
}


if($nome == '')
    $nome = "Desconhecido";


if (strtolower($codigo) != 'pt'){
//    header("location: semPermissao.php?p=" . $nome);
    include('semPermissao.php');
    exit;
}

?>
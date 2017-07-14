<?php

require __DIR__.'/../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
require __DIR__ . '/../../app/Lib/NotifyExclusion/autoload.php';

Dotenv::load(__DIR__ . '/../../');

$server = new SoapServer(__DIR__ . '/../../resources/wsdl/NotificacaoPedidoExclusao.wsdl');
$server->setClass('\App\Lib\NotifyExclusion\NotificacaoPedidoExclusaoServer');
$server->handle();
<?php

function autoload_2ede065b8e7c24e78ea99c34ced584cd($class)
{
    $classes = array(
        'App\Lib\NotifyExclusion\NotificacaoPedidoExclusaoServer' => __DIR__ .'/NotificacaoPedidoExclusaoServer.php',
        'App\Lib\NotifyExclusion\NotificacaoPedidoExclusaoType' => __DIR__ .'/NotificacaoPedidoExclusaoType.php',
        'App\Lib\NotifyExclusion\RespostaNotificacaoPedidoExclusaoType' => __DIR__ .'/RespostaNotificacaoPedidoExclusaoType.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_2ede065b8e7c24e78ea99c34ced584cd');

// Do nothing. The rest is just leftovers from the code generation.
{
}

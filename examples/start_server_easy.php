<?php

use ESD\BaseServer\ExampleClass\Server\DefaultServer;
use ESD\BaseServer\ExampleClass\Server\DefaultServerPort;
use ESD\Plugins\ProcessRPC\ExampleClass\TestProcess;
use ESD\Plugins\ProcessRPC\ProcessRPCPlugin;

require __DIR__ . '/../vendor/autoload.php';

define("ROOT_DIR", __DIR__ . "/..");
define("RES_DIR", __DIR__ . "/resources");
$server = new DefaultServer(null,DefaultServerPort::class,TestProcess::class);
$server->getPlugManager()->addPlug(new ProcessRPCPlugin());
//配置
$server->configure();
//启动
$server->start();

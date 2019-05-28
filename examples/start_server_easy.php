<?php

use ESD\Core\Server\Config\ServerConfig;
use ESD\Plugins\ProcessRPC\ExampleClass\TestProcess;
use ESD\Plugins\ProcessRPC\ProcessRPCPlugin;
use ESD\Server\Co\ExampleClass\DefaultServer;
use ESD\Server\Co\ExampleClass\Port\DefaultPort;

require __DIR__ . '/../vendor/autoload.php';

define("ROOT_DIR", __DIR__ . "/..");
define("RES_DIR", __DIR__ . "/resources");
$server = new DefaultServer(new ServerConfig(),DefaultPort::class,TestProcess::class);
$server->getPlugManager()->addPlug(new ProcessRPCPlugin());
//配置
$server->configure();
//启动
$server->start();

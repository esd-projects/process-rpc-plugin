<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/9
 * Time: 10:15
 */

namespace GoSwoole\Plugins\ProcessRPC;


use GoSwoole\BaseServer\Server\Process;
use GoSwoole\BaseServer\Server\Server;

trait GetProcessRpc
{
    public function callProcess(Process $process, string $className, float $timeOut = 5): RPCProxy
    {
        return new RPCProxy($process, $className, $timeOut);
    }

    public function callProcessName(string $processName, string $className, float $timeOut = 5): RPCProxy
    {
        $process = Server::$instance->getProcessManager()->getProcessFromName($processName);
        if ($process == null) {
            throw new ProcessRPCException("没有该进程");
        }
        return new RPCProxy($process, $className, $timeOut);
    }
}
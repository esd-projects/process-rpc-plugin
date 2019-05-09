<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/9
 * Time: 11:54
 */

namespace GoSwoole\Plugins\ProcessRPC\ExampleClass;

use GoSwoole\BaseServer\Server\Message\Message;
use GoSwoole\BaseServer\Server\Process;
use GoSwoole\Plugins\ProcessRPC\GetProcessRpc;

class TestProcess extends Process
{
    use GetProcessRpc;
    /**
     * 在onProcessStart之前，用于初始化成员变量
     * @return mixed
     */
    public function init()
    {
        // TODO: Implement init() method.
    }

    public function onProcessStart()
    {
        $rpc = $this->callProcessName("task-1",TestRPC::class);
        $this->log->debug($rpc->test());
    }

    public function onProcessStop()
    {
        // TODO: Implement onProcessStop() method.
    }

    public function onPipeMessage(Message $message, Process $fromProcess)
    {
        // TODO: Implement onPipeMessage() method.
    }
}
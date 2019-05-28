<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/9
 * Time: 11:54
 */

namespace ESD\Plugins\ProcessRPC\ExampleClass;

use ESD\Core\Message\Message;
use ESD\Core\Server\Process\Process;
use ESD\Plugins\ProcessRPC\GetProcessRpc;

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
        //不需要返回
        $rpc = $this->callProcessName("task-1",TestRPC::class,true);
        $this->log->debug($rpc->test());
        //rpc
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
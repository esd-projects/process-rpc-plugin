<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/9
 * Time: 10:17
 */

namespace GoSwoole\Plugins\ProcessRPC;


use GoSwoole\BaseServer\Server\Process;
use GoSwoole\BaseServer\Server\Server;

class RPCProxy
{
    /**
     * @var Process
     */
    private $process;
    /**
     * @var string
     */
    private $className;
    /**
     * @var float
     */
    private $timeOut;

    public function __construct(Process $process, string $className, float $timeOut = 0)
    {
        $this->process = $process;
        $this->className = $className;
        $this->timeOut = $timeOut;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws ProcessRPCException
     */
    public function __call($name, $arguments)
    {
        $message = new ProcessRPCCallMessage($this->className, $name, $arguments);
        $channel = RpcManager::getChannel($message->getProcessRPCCallData()->getToken());
        Server::$instance->getProcessManager()->getCurrentProcess()->sendMessage($message, $this->process);
        $result = $channel->pop($this->timeOut);
        $channel->close();
        if ($result instanceof ProcessRPCResultData) {
            if ($result->getErrorClass() != null) {
                throw new ProcessRPCException("[{$result->getErrorClass()}]{$result->getErrorMessage()}", $result->getErrorCode());
            } else {
                return $result->getResult();
            }
        } else {
            throw new ProcessRPCException("超时");
        }
    }
}
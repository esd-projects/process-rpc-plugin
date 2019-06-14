<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/9
 * Time: 10:17
 */

namespace ESD\Plugins\ProcessRPC;


use ESD\Core\Server\Process\Process;
use ESD\Core\Server\Server;

class RPCProxy
{
    /**
     * @var Process
     */
    protected $process;
    /**
     * @var string
     */
    protected $className;
    /**
     * @var float
     */
    protected $timeOut;
    /**
     * @var bool
     */
    protected $oneway;

    /**
     * @var int
     */
    protected $sessionId;

    public function __construct(Process $process, string $className, bool $oneway, float $timeOut = 0)
    {
        $this->process = $process;
        $this->className = $className;
        $this->timeOut = $timeOut;
        $this->oneway = $oneway;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws ProcessRPCException
     */
    public function __call($name, $arguments)
    {
        if ($this->sessionId != null) {
            $arguments['sessionId'] = $this->sessionId;
        }
        $message = new ProcessRPCCallMessage($this->className, $name, $arguments, $this->oneway);
        if (!$this->oneway) {
            $channel = RpcManager::getChannel($message->getProcessRPCCallData()->getToken());
        }
        Server::$instance->getProcessManager()->getCurrentProcess()->sendMessage($message, $this->process);
        if (!$this->oneway) {
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

    /**
     * 开启一个事务
     */
    public function startTransaction()
    {
        $oneway = $this->oneway;
        $this->oneway = true;
        try {
            $this->sessionId = $this->__call("__getSession", []);
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            $this->oneway = $oneway;
        }
    }

    /**
     * 结束一个事务
     */
    public function endTransaction()
    {
        $oneway = $this->oneway;
        $this->oneway = true;
        try {
            $this->__call("__clearSession", []);
        } catch (\Throwable $e) {
            throw $e;
        }finally{
            $this->oneway = $oneway;
        }
        $this->sessionId = null;
    }
}
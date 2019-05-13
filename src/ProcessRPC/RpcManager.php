<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/9
 * Time: 11:34
 */

namespace ESD\Plugins\ProcessRPC;


use ESD\BaseServer\Coroutine\Channel;
use ESD\BaseServer\Server\Server;

class RpcManager
{
    private static $token = 0;

    /**
     * @var Channel[];
     */
    private static $channelMap = [];

    public static function getToken()
    {
        return self::$token++;
    }

    public static function getChannel($token): Channel
    {
        self::$channelMap[$token] = new Channel();
        return self::$channelMap[$token];
    }

    public static function callChannel($token, $data)
    {
        $channel = self::$channelMap[$token] ?? null;
        if ($channel != null) {
            $channel->push($data);
            unset(self::$channelMap[$token]);
        }
    }
}
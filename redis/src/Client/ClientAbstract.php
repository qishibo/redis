<?php

namespace Redis\Client;

use Redis\Key;
use Redis\Proxy;
use Redis\Hash;

abstract class ClientAbstract
{
    private $hash;
    private $redis;
    private $keyCalculator;

    abstract function doExec($method, $params);
    // abstract function execute($method, $params);

    public function __construct(array $config, $redisExtension)
    {
        $this->config         = $config;
        $this->redisExtension = $redisExtension;
    }

    public function __call($method, $params)
    {
        return $this->doExec($method, $params);
    }


    public function setRedisExtension($redisExtension)
    {
        $this->redisExtension = $redisExtension;
    }

    public function createConnection(array $config)
    {
        return Proxy\RedisFactory::getRedis($config, $this->redisExtension);
    }
}

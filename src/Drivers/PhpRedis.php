<?php

namespace Redis\Drivers;

class PhpRedis implements DriversInterface
{
    public function __construct(array $config)
    {
        $this->config = $config;

        try {
            $this->redis  = new \Redis();
        }
        catch (\Exception $e) {
            exit($e->geeMessage() . ' Please check your phpredis extension...');
        }

        try {
            $this->redis->connect($config['host'], $config['port']);
        }
        catch (\Exception $e) {
            exit($e->geeMessage() . ' Cant\'t create connection with your phpredis extension...');
        }
    }

    public function execute($method, $params)
    {
        !is_array($params) && $params = (array) $params;

        return call_user_func_array([$this->redis, $method], $params);
    }

    public function __destruct()
    {
        $this->redis->close();
    }
}
// end of file PhpRedis.php

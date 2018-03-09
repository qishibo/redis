<?php

namespace Redis\Drivers;

class PhpRedis implements DriversInterface
{
    /**
     * @var array config
     */
    private $config;

    /**
     * @var \Redis redis instance
     */
    private $redis;

    /**
     * __construct
     *
     * @param array $config config of redis, include host, port, weight
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        try {
            $this->redis  = new \Redis();
        }
        catch (\Exception $e) {
            exit($e->getMessage() . ' Please check your phpredis extension...');
        }

        try {
            $this->redis->connect($config['host'], $config['port']);
        }
        catch (\Exception $e) {
            // todo: define manual Exception
            throw new \Exception('Cant\'t create connection to redis server, ' . $e->getMessage());
        }

        isset($config['auth']) && $this->redis->auth($config['auth']);
    }

    /**
     * call redis class to executing methods finally
     *
     * @param  string $method redis method
     * @param  array  $params params
     *
     * @return mixed          result
     */
    public function execute($method, $params)
    {
        !is_array($params) && $params = (array) $params;

        return call_user_func_array([$this->redis, $method], $params);
    }

    /**
     * close the redis connection
     */
    public function __destruct()
    {
        $this->redis->close();
    }
}

// end of file PhpRedis.php

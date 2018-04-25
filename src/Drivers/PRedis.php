<?php

namespace Redis\Drivers;

class PRedis implements DriversInterface
{
    /**
     * __construct.
     *
     * @param array $config config of redis, include host, port, weight
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        // $this->redis = new \Redis($config['host'], $config['port']);
    }

    /**
     * call redis class to executing methods finally.
     *
     * @param string $method redis method
     * @param array  $params params
     *
     * @return mixed result
     */
    public function execute($method, $params)
    {
        !is_array($params) && $params = (array) $params;

        // return call_user_func_array([$this->redis, $method], $params);
    }

    /**
     * close the redis connection.
     */
    public function __destruct()
    {
        // $this->redis->close();
    }
}

// end of file PRedis.php

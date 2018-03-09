<?php

namespace Redis\Drivers;

class PRedis implements DriversInterface
{
    /**
     * @var array config
     */
    private $config;

    /**
     * @var  redis instance
     */
    private $redis;

    /**
     * __construct
     *
     * @param array $config config of redis, include host, port, weight
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        exit('not support yet!');
    }

    /**
     * function execute
     *
     * @param $method
     * @param $params
     *
     */
    public function execute($method, $params) {}

    public function __destruct() {}
}

// end of file PRedis.php

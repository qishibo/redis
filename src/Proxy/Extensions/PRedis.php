<?php

namespace Redis\Proxy\Extensions;

class PRedis extends ExtensionsAbstract
{
    public function __construct(array $config)
    {
        $this->config = $config;
        // $this->redis = new \Redis($config['host'], $config['port']);
    }

    public function execute($method, $params)
    {
        $this->redis->$method($params);
    }
}
// end of file PRedis.php

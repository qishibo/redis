<?php

namespace Redis;

use Redis\Drivers\DriversInterface;
use Redis\Drivers\RedisFactory;
use Redis\Client\ClientAbstract;

class SingleClient extends ClientAbstract
{
    /**
     * @var DriversInterface redis instance
     */
    private $link;

    /**
     * __construct
     *
     * @param      array   $config          config of redis, include host, port, weight
     * @param      string  $redisExtension  type of php redis extension
     */
    public function __construct(
        array $config,
        $redisExtension = RedisFactory::PHPREDIS
    )
    {
        parent::__construct($config, $redisExtension);
    }

    /**
     * call the redis object to execute data
     *
     * @param      string   $method  method
     * @param      array   $params  params
     *
     * @return     boolean|mixed
     */
    public function doExec($method, $params)
    {
        return $this->getConnection()->execute($method, $params);
    }

    /**
     * get new redis object, if not cached
     *
     * @return     DriversInterface
     */
    private function getConnection()
    {
        if (!is_null($this->link)) {
            return $this->link;
        }
        return $this->link = $this->createConnection($this->config);
    }

}

// end of file SingleClient.php

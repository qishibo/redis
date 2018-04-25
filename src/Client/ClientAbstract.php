<?php

namespace Redis\Client;

use Redis\Drivers;
use Redis\Key;

abstract class ClientAbstract
{
    protected $config;
    private $denyOperates = [];

    /**
     * method which child class must declare,for executing redis methods.
     */
    abstract public function doExec($method, $params);

    /**
     * __construct.
     *
     * @param array  $config         config of redis, include host, port, [weight]
     * @param string $redisExtension type of php redis extension
     */
    public function __construct(array $config, $redisExtension)
    {
        $this->config = $config;
        $this->redisExtension = $redisExtension;

        $this->denyOperates = $this->getDenyOperates();
    }

    /**
     * magic method for redis client.
     *
     * @param string $method method
     * @param array  $params params
     *
     * @return mixed|bool result
     */
    public function __call($method, $params)
    {
        $method = strtolower($method);

        // if exec some denied methods,forbidden
        if (in_array($method, $this->denyOperates)) {
            return false;
        }

        // $params[0] is the redis key,and it won't be empty! except randomkey
        if (empty($params[0]) && ($method != 'randomkey')) {
            return false;
        }

        // when randomkey, $params is an empty array,here for avoid php warning
        empty($params[0]) && $params[0] = '';

        return $this->doExec($method, $params);
    }

    /**
     * set php extensions, such as PHPREDIS, PREDIS
     * see more in Redis\Drivers\RedisFactory.
     *
     * @param string $redisExtension Redis\Drivers\RedisFactory::PHPREDIS
     */
    public function setRedisExtension($redisExtension)
    {
        $this->redisExtension = $redisExtension;
    }

    /**
     * get redis connection.
     *
     * @param array $config config
     *
     * @return Redis\Drivers\DriversInterface
     */
    public function createConnection(array $config)
    {
        return Drivers\RedisFactory::getRedis($config, $this->redisExtension);
    }

    /**
     * get denied executions,you need to add some methods if you need.
     *
     * @return array
     */
    private function getDenyOperates()
    {
        return ['setoption', 'save', 'bgsave', 'flushdb', 'flushall', 'setoption', 'shutdown', 'slaveof'];
    }
}

// end of file ClientAbstract.php

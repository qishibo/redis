<?php

namespace Redis\Client;

use Redis\Drivers\RedisFactory;

abstract class ClientAbstract
{
    /**
     * @var array config
     */
    protected $config;

    /**
     * @var array denied commands
     */
    private $denyOperates = [];

    /**
     * @var string php redis extension type
     */
    private $redisExtension;

    /**
     * method which child class must declare, for executing redis methods
     *
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    abstract function doExec($method, $params);

    /**
     * __construct
     *
     * @param     array    $config          config of redis, include host, port, [weight]
     * @param     string   $redisExtension  type of php redis extension
     */
    public function __construct(array $config, $redisExtension = null)
    {
        $this->config         = $config;
        $this->redisExtension = $redisExtension ?: RedisFactory::PHPREDIS;
        $this->denyOperates   = $this->getDenyOperations();
    }

    /**
     * magic method for redis client
     *
     * @param     string   $method  method
     * @param     array    $params  params
     *
     * @return    mixed|boolean     result
     * @throws    \Exception
     */
    public function __call($method, $params)
    {
        $method = strtolower($method);

        // if exec some denied methods,forbidden
        if (in_array($method, $this->denyOperates)) {
            throw new \Exception("command $method is not allowed!");
        }

        // $params[0] is the redis key,and it won't be empty! except randomkey
        if (empty($params[0]) && ($method !== 'randomkey')) {
            return false;
        }

        // when randomkey, $params is an empty array,here for avoid php warning
        empty($params[0]) && $params[0] = '';

        return $this->doExec($method, $params);
    }

    /**
     * set php extensions, such as PHPREDIS, PREDIS
     * see more in \Redis\Drivers\RedisFactory
     *
     * @param      string  $redisExtension
     *
     * @return $this
     */
    public function setRedisExtension($redisExtension)
    {
        $this->redisExtension = $redisExtension;
        return $this;
    }

    /**
     * get redis connection
     *
     * @param      array   $config  config
     *
     * @return     \Redis\Drivers\DriversInterface
     */
    public function createConnection(array $config)
    {
        return RedisFactory::getRedis($config, $this->redisExtension);
    }

    /**
     * set redis denied commands
     *
     * @param array $commands
     *
     * @return $this
     */
    public function setDenyOperations(array $commands = [])
    {
        $this->denyOperates = $commands;
        return $this;
    }

    /**
     * get denied executions,you need to add some methods if you need
     *
     * @return     array
     */
    public function getDenyOperations()
    {
        return [
            'setoption',
            'save',
            'bgsave',
            'flushdb',
            'flushall',
            'setoption',
            'shutdown',
            'slaveof'
        ];
    }
}

// end of file ClientAbstract.php

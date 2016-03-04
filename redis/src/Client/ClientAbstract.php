<?php

namespace Redis\Client;

use Redis\Key;
use Redis\Proxy;
use Redis\Hash;

abstract class ClientAbstract
{
    protected $config;
    private $denyOperates = [];

    abstract function doExec($method, $params);

    public function __construct(array $config, $redisExtension)
    {
        $this->config         = $config;
        $this->redisExtension = $redisExtension;

        $this->denyOperates   = $this->getDenyOperates();
    }

    public function __call($method, $params)
    {
        $method = strtolower($method);

        // if exec some denied methods,forbidden
        if (in_array($method, $this->denyOperates)) {
            return false;
        }

        return $this->doExec($method, $params);
    }

    /**
     * set php extensions, such as PHPREDIS, PREDIS
     * see more in Redis\Proxy\RedisFactory
     *
     * @param      string  $redisExtension  Redis\Proxy\RedisFactory::PHPREDIS
     */
    public function setRedisExtension($redisExtension)
    {
        $this->redisExtension = $redisExtension;
    }

    /**
     * get redis connection
     *
     * @param      array   $config  config
     *
     * @return     Redis\Proxy\Extensions\ExtensionsAbstract
     */
    public function createConnection(array $config)
    {
        var_dump('renew redis ing...port is ' . $config['port']);
        return Proxy\RedisFactory::getRedis($config, $this->redisExtension);
    }

    /**
     * get denied executions,you need to add some methods if you need
     *
     * @return     array
     */
    private function getDenyOperates()
    {
        return ['setoption', 'save', 'bgsave', 'flushdb', 'flushall', 'setoption', 'shutdown', 'slaveof'];
    }
}

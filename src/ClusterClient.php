<?php

namespace Redis;

use Redis\Client\ClusterClientAbstract;
use Redis\Drivers\DriversInterface;
use Redis\Hash\HashInterface;
use Redis\Key\KeyInterface;
use Redis\Drivers\RedisFactory;

class ClusterClient extends ClusterClientAbstract
{
    /**
     * __construct
     *
     * @param      array              $config          config of redis, include host, port, weight
     * @param      string             $redisExtension  type of php redis extension
     * @param      HashInterface  $hash            hash object
     * @param      KeyInterface    $keyCalculator   keyCalculator object
     */
    public function __construct(
        array $config,
        HashInterface $hash = null,
        KeyInterface $keyCalculator = null,
        $redisExtension = RedisFactory::PHPREDIS
    )
    {
        parent::__construct([$config], $hash, $keyCalculator, $redisExtension);
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
        $node  = $this->hash->lookUp($params[0]);

        return $this->getConnection($node)->execute($method, $params);
    }

    /**
     * get new redis object, if not cached
     *
     * @param      string  $node         hash node in the hash ring
     *
     * @return     DriversInterface
     */
    private function getConnection($node)
    {
        if (isset($this->links[$node])) {
            return $this->links[$node];
        }

        $redisNode2RealNode = str_replace($this->nodePre, '', $node);

        return $this->links[$node] = $this->createConnection($this->config[0][$redisNode2RealNode]);
    }
}

// end of file ClusterClient.php

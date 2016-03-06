<?php

namespace Redis\Client;

use Redis\Proxy;
use Redis\Hash;
use Redis\Key;

class WithoutSlavesClient extends ClusterClientAbstract
{
    /**
     * __construct
     *
     * @param      array              $config          config of redis, include host, port, weight
     * @param      string             $redisExtension  type of php redis extension
     * @param      Hash\HashAbstract  $hash            hash object
     * @param      Key\KeyAbstract    $keyCalculator   keyCalculator object
     */
    public function __construct(
        array $config,
        $redisExtension = Proxy\RedisFactory::PHPREDIS,
        Hash\HashAbstract $hash,
        Key\KeyAbstract $keyCalculator
    )
    {
        parent::__construct([$config], $redisExtension, $hash, $keyCalculator);
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
     * @return     Redis\Proxy\Extensions\ExtensionsAbstract
     */
    private function getConnection($node)
    {
        if (isset($this->links[$node])) {
            return $this->links[$node];
        }

        $redisNode2RealNode = str_replace($this->nodePre, '', $node);

        return $this->links[$node] = $this->createConnection($this->config[0][$redisNode2RealNode], $this->redisExtension);
    }
}
// end of file WithoutSlavesClient.php

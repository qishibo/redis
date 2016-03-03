<?php

namespace Redis\Client;

use Redis\Proxy;
use Redis\Hash;
use Redis\Key;

class WithSlavesClient extends ClusterClientAbstract
{
    private $masterOperate;
    private $slaveOperate;

    public function __construct(
        array $config,
        $redisExtension = Proxy\RedisFactory::PHPREDIS,
        Hash\HashAbstract $hash,
        Key\KeyAbstract $keyCalculator
    )
    {
        parent::__construct($config, $redisExtension, $hash, $keyCalculator);

        $this->masterOperate = $this->getMasterOperate();
        $this->slaveOperate  = $this->getSlaveOperate();
    }

    public function doExec($method, $params)
    {
        // $params[0] is the redis key,and it won't be empty!
        if (empty($params[0])) {
            return false;
        }
        $node = $this->hash->lookUp($params[0]);

        if ($this->getType($method) === 'm') {
            $redis = $this->getConnection('m', $node);
        }
        else {
            $redis = $this->getConnection('s', $node);
        }
// var_dump($redis);die;
        return $redis->execute($method, $params);
    }

    private function getType($method)
    {
        if (in_array($method, $this->masterOperate)) {
            return 'm';
        }
        return 's';
    }

    private function getConnection($type, $node)
    {
        if (isset($this->links[$type][$node])) {
            return $this->links[$type][$node];
        }
        $redisNode2RealNode = str_replace($this->nodePre, '', $node);
        // var_dump($node, $this->nodePre, $redisNode2RealNode);die;
        // var_dump($this->config);die;
        return $this->links[$type][$node] = $this->createConnection($this->config[$type][$redisNode2RealNode], $this->redisExtension);
    }

    private function getMasterOperate()
    {
        return ['delete', 'set', 'hset', 'zadd'];
    }

    private function getSlaveOperate()
    {
        return ['get', 'hget', 'zscore'];
    }

}

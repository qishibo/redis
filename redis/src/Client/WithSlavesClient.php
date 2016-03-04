<?php

namespace Redis\Client;

use Redis\Proxy;
use Redis\Hash;
use Redis\Key;

class WithSlavesClient extends ClusterClientAbstract
{

    const MASTER = 'm';
    const SLAVE = 's';

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
        // $params[0] is the redis key,and it won't be empty! except randomkey
        if (empty($params[0]) && ($method != 'randomkey')) {
            return false;
        }

        // randomkey $params is an empty array,here for avoid warning
        empty($params[0]) && $params[0] = '';

        $node = $this->hash->lookUp($params[0]);

        if ($this->getType($method) === self::MASTER) {
            $redis = $this->getConnection(self::MASTER, $node);
        }
        else {
            $redis = $this->getConnection(self::SLAVE, $node);
        }
// var_dump($redis);die;
        return $redis->execute($method, $params);
    }

    private function getType($method)
    {
        if (in_array($method, $this->masterOperate)) {
            return self::MASTER;
        }
        return self::SLAVE;
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
        return ['set', 'setex', 'setnx', 'del', 'delete', 'incr', 'incrby', 'decr', 'decrby', 'lpush', 'rpush', 'lpushx', 'rpushx', 'lpop', 'rpop', 'blpop', 'brpop', 'lset', 'ltrim', 'listtrim', 'lrem', 'lremove', 'linsert', 'sadd', 'srem', 'sremove', 'smove', 'spop', 'getset', 'move', 'rename', 'renamekey', 'renamenx', 'settimeout', 'expire', 'expireat', 'append', 'setrange', 'setbit', 'sort', 'persist', 'mset', 'rpoplpush', 'zadd', 'zdelete', 'zrem', 'zremrangebyscore', 'zdeleterangebyscore', 'zincrby', 'hset', 'hincrby', 'hmset', 'hdel'];
    }

    private function getSlaveOperate()
    {
        return ['get', 'exists', 'getmultiple', 'lsize', 'lindex', 'lget', 'lrange', 'lgetrange', 'sismember', 'scontains', 'scard', 'ssize', 'srandmember', 'sinter', 'sinterstore', 'sunion', 'sunionstore', 'sdiff', 'sdiffstore', 'smembers', 'sgetmembers', 'randomkey', 'keys', 'getkeys', 'dbsize', 'type', 'getrange', 'strlen', 'getbit', 'info', 'ttl', 'zrange', 'zrevrange', 'zrangebyscore', 'zrevrangebyscore', 'zcount', 'zsize', 'zcard', 'zscore', 'zrank', 'zrevrank', 'zunion', 'zinter', 'hget', 'hlen', 'hkeys', 'hvals', 'hgetall', 'hexists', 'hmget'];
    }

}

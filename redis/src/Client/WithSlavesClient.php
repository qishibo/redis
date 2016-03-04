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
        parent::__construct($config, $redisExtension, $hash, $keyCalculator);

        $this->masterOperate = $this->getMasterOperate();
        $this->slaveOperate  = $this->getSlaveOperate();
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
        // $params[0] is the redis key,and it won't be empty! except randomkey
        if (empty($params[0]) && ($method != 'randomkey')) {
            return false;
        }

        // when randomkey, $params is an empty array,here for avoid php warning
        empty($params[0]) && $params[0] = '';

        $node  = $this->hash->lookUp($params[0]);

        $redis = $this->getConnection($this->getType($method), $node);

        return $redis->execute($method, $params);
    }

    /**
     * return redis operateType ,such set should return 'm', get should return 's'
     *
     * @param      string  $method  redis method
     *
     * @return     string  type of operate
     */
    private function getType($method)
    {
        if (in_array($method, $this->masterOperate)) {
            return self::MASTER;
        }
        return self::SLAVE;
    }

    /**
     * get new redis object by operateType and node, if not cached
     *
     * @param      string  $operateType  redis operateType
     * @param      string  $node         hash node in the hash ring
     *
     * @return     Redis\Proxy\Extensions\ExtensionsAbstract
     */
    private function getConnection($operateType, $node)
    {
        if (isset($this->links[$operateType][$node])) {
            return $this->links[$operateType][$node];
        }

        $redisNode2RealNode = str_replace($this->nodePre, '', $node);

        return $this->links[$operateType][$node] = $this->createConnection($this->config[$operateType][$redisNode2RealNode], $this->redisExtension);
    }

    /**
     * redis master operates
     * following operates executes in master server
     *
     * @return     array
     */
    private function getMasterOperate()
    {
        return ['set', 'setex', 'setnx', 'del', 'delete', 'incr', 'incrby', 'decr', 'decrby', 'lpush', 'rpush', 'lpushx', 'rpushx', 'lpop', 'rpop', 'blpop', 'brpop', 'lset', 'ltrim', 'listtrim', 'lrem', 'lremove', 'linsert', 'sadd', 'srem', 'sremove', 'smove', 'spop', 'getset', 'move', 'rename', 'renamekey', 'renamenx', 'settimeout', 'expire', 'expireat', 'append', 'setrange', 'setbit', 'sort', 'persist', 'mset', 'rpoplpush', 'zadd', 'zdelete', 'zrem', 'zremrangebyscore', 'zdeleterangebyscore', 'zincrby', 'hset', 'hincrby', 'hmset', 'hdel'];
    }

    /**
     * redis slave operates
     * following operates executes in slave server
     *
     * @return     array
     */
    private function getSlaveOperate()
    {
        return ['get', 'exists', 'getmultiple', 'lsize', 'lindex', 'lget', 'lrange', 'lgetrange', 'sismember', 'scontains', 'scard', 'ssize', 'srandmember', 'sinter', 'sinterstore', 'sunion', 'sunionstore', 'sdiff', 'sdiffstore', 'smembers', 'sgetmembers', 'randomkey', 'keys', 'getkeys', 'dbsize', 'type', 'getrange', 'strlen', 'getbit', 'info', 'ttl', 'zrange', 'zrevrange', 'zrangebyscore', 'zrevrangebyscore', 'zcount', 'zsize', 'zcard', 'zscore', 'zrank', 'zrevrank', 'zunion', 'zinter', 'hget', 'hlen', 'hkeys', 'hvals', 'hgetall', 'hexists', 'hmget'];
    }

}

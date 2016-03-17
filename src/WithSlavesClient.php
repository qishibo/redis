<?php

namespace Redis;

class WithSlavesClient extends Client\ClusterClientAbstract
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
     * @param      Hash\HashInterface  $hash            hash object
     * @param      Key\KeyInterface    $keyCalculator   keyCalculator object
     */
    public function __construct(
        array $config,
        Hash\HashInterface $hash,
        Key\KeyInterface $keyCalculator,
        $redisExtension = Drivers\RedisFactory::PHPREDIS
    )
    {
        parent::__construct($config, $hash, $keyCalculator, $redisExtension);

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
        $node  = $this->hash->lookUp($params[0]);

        return $this->getConnection($this->getType($method), $node)->execute($method, $params);
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
     * @return     Redis\Drivers\DriversInterface
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

// end of file WithSlavesClient.php

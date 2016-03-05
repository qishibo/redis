<?php

namespace Redis\Client;

use Redis\Proxy;

class SingleClient extends ClientAbstract
{
    private $link;

    /**
     * __construct
     *
     * @param      array   $config          config of redis, include host, port, weight
     * @param      string  $redisExtension  type of php redis extension
     */
    public function __construct(
        array $config,
        $redisExtension = Proxy\RedisFactory::PHPREDIS
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
        // $params[0] is the redis key,and it won't be empty! except randomkey
        if (empty($params[0]) && ($method != 'randomkey')) {
            return false;
        }

        // when randomkey, $params is an empty array,here for avoid php warning
        empty($params[0]) && $params[0] = '';

        $reids = $this->getConnection();

        return $redis->execute($method, $params);
    }

    /**
     * get new redis object, if not cached
     *
     * @return     Redis\Proxy\Extensions\ExtensionsAbstract
     */
    private function getConnection()
    {
        if (!is_null($this->link)) {
            return $this->link;
        }
        return $this->link = $this->createConnection($this->config, $this->redisExtension);;
    }

}

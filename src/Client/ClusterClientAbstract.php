<?php

namespace Redis\Client;

use Redis\Hash;
use Redis\Key;

abstract class ClusterClientAbstract extends ClientAbstract
{
    protected $links;
    protected $hash;
    protected $keyCalculator;

    protected $nodePre = 'qii';

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
        parent::__construct($config, $redisExtension);
        $this->init($hash, $keyCalculator);
    }

    /**
     * init
     *
     * @param      Hash\HashAbstract  $hash           hash object
     * @param      Key\KeyAbstract    $keyCalculator  keyCalculator object
     */
    private function init($hash, $keyCalculator)
    {
        $this->hash          = $hash;
        $this->keyCalculator = $keyCalculator;

        $this->hash->setKeyCalculator($this->keyCalculator);

        foreach (reset($this->config) as $rawNode => $config) {
            // default weight is 1
            empty($config['weight']) && $config['weight'] = 1;

            $this->hash->addNode($this->nodePre . $rawNode, $config['weight']);
        }
    }

    /**
     * set hash object for find node, such as consistant hash
     *
     * @param      Hash\HashAbstract  $hash   hash pbject
     */
    public function setHashStragety(Hash\HashAbstract $hash)
    {
        $this->hash = $hash;
        $this->hash->setKeyCalculator($this->keyCalculator);
    }

    /**
     * set KeyCalculator to hash object, for calcing key to hash number
     *
     * @param      Key\KeyAbstract  $keyCalculator  keyCalculator object
     */
    public function setKeyCalculator(Key\KeyAbstract $keyCalculator)
    {
        $this->keyCalculator = $keyCalculator;
        $this->hash->setKeyCalculator($keyCalculator);
    }

    /**
     * set the prefix for the node
     * if raw node is 'u0' in your config, then the node add to the hash ring will change to 'qiiu0'
     *
     * @param      string  $nodePre  prefix of the node
     */
    public function setNodePre($nodePre)
    {
        if (is_string($nodePre)) {
            $this->nodePre = $nodePre;
        }
    }
}
// end of file ClusterClientAbstract.php

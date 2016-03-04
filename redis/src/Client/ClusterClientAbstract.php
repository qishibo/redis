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

    public function __construct(
        array $config,
        $redisExtension = Proxy\RedisFactory::PHPREDIS,
        Hash\HashAbstract $hash,
        Key\KeyAbstract $keyCalculator
    )
    {
        parent::__construct($config, $redisExtension);
        $this->init($config, $redisExtension, $hash, $keyCalculator);
    }

    private function init($config, $redisExtension, $hash, $keyCalculator)
    {
        $this->hash = $hash;
        $this->keyCalculator = $keyCalculator;

        $this->hash->setKeyCalculator($this->keyCalculator);

        foreach (reset($this->config) as $index => $hostPort) {
            $this->hash->addNode($this->nodePre . $index);
        }
    }

    public function setHashStragety(Hash\HashAbstract $hash)
    {
        $this->hash = $hash;
        $this->hash->setKeyCalculator($this->keyCalculator);
    }

    public function setKeyCalculator(Key\KeyAbstract $keyCalculator)
    {
        $this->keyCalculator = $keyCalculator;
        $this->hash->setKeyCalculator($keyCalculator);
    }

    public function setNodePre($nodePre)
    {
        if (is_string($nodePre)) {
            $this->nodePre = $nodePre;
        }
    }
}

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
        $this->init($hash, $keyCalculator);
    }

    private function init($hash, $keyCalculator)
    {
        $this->hash = $hash;
        $this->keyCalculator = $keyCalculator;

        $this->hash->setKeyCalculator($this->keyCalculator);

        foreach (reset($this->config) as $rawNode => $config) {
            // default weight is 1
            empty($config['weight']) && $config['weight'] = 1;

            $this->hash->addNode($this->nodePre . $rawNode, $config['weight']);
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

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
        // parent::__construct();
        $this->init($config, $redisExtension, $hash, $keyCalculator);
    }

    private function init($config, $redisExtension, $hash, $keyCalculator)
    {
        $this->hash = $hash;
        // var_dump($config, $redisExtension, $hash, $keyCalculator);die;
        $this->hash->keyCalculator = $keyCalculator;
        $this->config = $config;
        $this->redisExtension = $redisExtension;

        foreach ($config['m'] as $index => $hostPort) {
            // var_dump($source);
            $this->hash->addNode($this->nodePre . $index);
        }
    }

    public function setHashStragety(Hash\HashAbstract $hash)
    {
        $this->hash = $hash;
    }

    public function setKeyCalculator(Key\KeyAbstract $keyCalculator)
    {
        $this->keyCalculator = $keyCalculator;
    }
    // public function execute()
    // {
    //     //
    // }

    // abstract function getLink();
    // abstract function doExec();

}

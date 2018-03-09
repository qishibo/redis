<?php

namespace Redis\Client;

use Redis\Hash\HashInterface;
use Redis\Hash\Consistent;
use Redis\Key\KeyInterface;
use Redis\Key\Cr32;

abstract class ClusterClientAbstract extends ClientAbstract
{
    /**
     * @var array link pools
     */
    protected $links;

    /**
     * @var HashInterface hasher
     */
    protected $hash;

    /**
     * @var KeyInterface key calculator
     */
    protected $keyCalculator;

    /**
     * @var string node pre
     */
    protected $nodePre = 'qii';

    /**
     * __construct
     *
     * @param      array          $config          config of redis, include host, port, weight
     * @param      HashInterface  $hash            hash object
     * @param      KeyInterface   $keyCalculator   keyCalculator object
     * @param      string         $redisExtension  type of php redis extension
     */
    public function __construct(
        array $config,
        HashInterface $hash = null,
        KeyInterface $keyCalculator = null,
        $redisExtension = null
    )
    {
        parent::__construct($config, $redisExtension);
        $this->init($hash, $keyCalculator);
    }

    /**
     * init
     *
     * @param      HashInterface  $hash           hash object
     * @param      KeyInterface   $keyCalculator  keyCalculator object
     */
    private function init($hash, $keyCalculator)
    {
        $this->hash          = $hash ?: new Consistent();
        $this->keyCalculator = $keyCalculator ?: new Cr32();

        $this->hash->setKeyCalculator($this->keyCalculator);

        foreach (reset($this->config) as $rawNode => $config) {
            // default weight is 1
            empty($config['weight']) && $config['weight'] = 1;

            $this->hash->addNode($this->nodePre . $rawNode, $config['weight']);
        }
    }

    /**
     * set hash object for find node, such as consistent hash
     *
     * @param      HashInterface  $hash   hash pbject
     */
    public function setHashStragety(HashInterface $hash)
    {
        $this->hash = $hash;
        $this->hash->setKeyCalculator($this->keyCalculator);
    }

    /**
     * set KeyCalculator to hash object, for calculating key to hash number
     *
     * @param      KeyInterface  $keyCalculator  keyCalculator object
     */
    public function setKeyCalculator(KeyInterface $keyCalculator)
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

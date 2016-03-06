<?php

namespace Redis\Hash;

use Redis\Key;

abstract class HashAbstract
{
    /**
     * add server node to the hash ring
     */
    abstract function addNode($node, $weight);

    /**
     * lookup the right node by key
     */
    abstract function lookUp($key);

    /**
     * set the KeyCalculator, which use for calcing key to hash Number
     */
    abstract function setKeyCalculator(Key\KeyAbstract $keyCalculator);

}

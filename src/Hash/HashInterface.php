<?php

namespace Redis\Hash;

use Redis\Key;

interface HashInterface
{
    /**
     * add server node to the hash ring
     */
    public function addNode($node, $weight);

    /**
     * lookup the right node by key
     */
    public function lookUp($key);

    /**
     * set the KeyCalculator, which use for calcing key to hash Number
     */
    public function setKeyCalculator(Key\KeyInterface $keyCalculator);

}

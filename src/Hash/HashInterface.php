<?php

namespace Redis\Hash;

use Redis\Key\KeyInterface;

interface HashInterface
{
    /**
     * add server node to the hash ring
     *
     * @param string $node node name
     * @param int $weight node weight
     *
     * @return mixed
     */
    public function addNode($node, $weight);

    /**
     * lookup the right node by key
     *
     * @param string $key
     *
     * @return string node
     */
    public function lookUp($key);

    /**
     * set the KeyCalculator, which use for calculating key to hash Number
     *
     * @param KeyInterface $keyCalculator
     *
     * @return self
     */
    public function setKeyCalculator(KeyInterface $keyCalculator);

}

// end of file HashInterface.php

<?php

namespace Redis\Hash;

use Redis\Key\KeyInterface;

class Consistent implements HashInterface
{
    /**
     * @var KeyInterface key calc
     */
    public $keyCalculator;

    /**
     * @var array position2nodes
     */
    private $position2Node;

    /**
     * @var int default replicas
     */
    private $replicas = 128;

    /**
     * @var bool key sorted flag
     */
    private $positionSorted = false;

    /**
     * add server node to the hash ring
     *
     * @param      string   $node    server node name
     * @param      integer  $weight  weight in the servers
     *
     * @return void
     */
    public function addNode($node, $weight = 1)
    {
        $nodeCount = ceil($this->replicas * $weight);

        for ($i=0; $i < $nodeCount; $i++) {
            $position = $this->keyCalculator->calc($node . $i);
            $this->position2Node[$position] = $node;
        }
    }

    /**
     * lookup the right node by key
     *
     * @param      string  $key    redis key
     *
     * @return     string  server node
     */
    public function lookUp($key)
    {
        $this->checkSortNode();

        $keyPosition = $this->keyCalculator->calc($key);

        foreach ($this->position2Node as $position => $node) {
            if ($position > $keyPosition) {
                return $node;
            }
        }

        // key is bigger than the max node hash,return the first node
        return reset($this->position2Node);
    }

    /**
     * set the KeyCalculator, which use for calculating key to hash Number
     *
     * @param      KeyInterface  $keyCalculator  KeyCalculator
     *
     * @return self
     */
    public function setKeyCalculator(KeyInterface $keyCalculator)
    {
        $this->keyCalculator = $keyCalculator;
        return $this;
    }

    /**
     * sort the position2Node array,make it a right consistent hash ring
     */
    private function checkSortNode()
    {
        if (!$this->positionSorted) {
            ksort($this->position2Node);
            $this->positionSorted = true;
        }
    }
}

// end of file Consistent.php

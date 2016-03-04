<?php

namespace Redis\Hash;

use Redis\Key;

class Consistant extends HashAbstract
{
    public $keyCalculator;
    private $position2Node;

    private $replicas = 128;

    private $positionSorted = false;

    /**
     * add server node to the hash ring
     *
     * @param      string   $node    server node name
     * @param      integer  $weight  weight in the servers
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
     * set the KeyCalculator, which use for calcing key to hash Number
     *
     * @param      Key\KeyAbstract  $keyCalculator  KeyCalculator
     */
    public function setKeyCalculator(Key\KeyAbstract $keyCalculator)
    {
        $this->keyCalculator = $keyCalculator;
    }

    /**
     * sort the position2Node array,make it a right consistant hash ring
     */
    private function checkSortNode()
    {
        if (!$this->positionSorted) {
            ksort($this->position2Node);
            $this->positionSorted = true;
        }
    }
}
// end of file Consistant.php

<?php

namespace Redis\Hash;
use Redis\Key;

class Consistant extends HashAbstract
{
    public $keyCalculator;
    // private $node2Position;
    private $position2Node;

    private $replicas = 128;

    private $positionSorted = false;

    public function addNode($node, $weight = 1)
    {
        $nodeCount = ceil($this->replicas * $weight);

        for ($i=0; $i < $nodeCount; $i++) {
            $position = $this->keyCalculator->calc($node . $i);
            // $this->node2Position[$node][] = $position;
            $this->position2Node[$position] = $node;
        }
    }
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
     * sort the position2Node array,make it a right consistant hash ring
     */
    private function checkSortNode()
    {
        if (!$this->positionSorted) {
            ksort($this->position2Node);
            $this->positionSorted = true;
        }
    }

    public function setKeyCalculator(Key\KeyAbstract $keyCalculator)
    {
        $this->keyCalculator = $keyCalculator;
    }
}

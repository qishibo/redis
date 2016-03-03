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

    public function addNode($server, $weight = 1)
    {
        // $serverNode = $this->keyCalculator->calc($server);
        $nodeCount = $this->replicas * $weight;

        for ($i=0; $i < $nodeCount; $i++) {
            $position = $this->keyCalculator->calc($server.$i);
            // $this->node2Position[$server][] = $position;
            $this->position2Node[$position] = $server;
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

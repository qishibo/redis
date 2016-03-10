<?php

use Redis\Key;
use Redis\Hash\Consistant;

class ConsistantHashTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->keyCalculator = new Key\Cr32();
    }
    /**
     * Test addNode();
     */
    public function testaddNode()
    {
        $hash = new Consistant();
        $hash->setKeyCalculator($this->keyCalculator);

        $node = 'u0';

        $hash->addNode($node);

        $findNode = $hash->lookUp('qii404');

        $this->assertEquals($node, $findNode);
    }
}
// end of file Cr32Test.php

<?php

use Redis\Key;
use Redis\Hash\Consistant;

class ConsistantHashTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test addNode();
     */
    public function testaddNodeCr32()
    {
        $hash = new Consistant();
        $hash->setKeyCalculator(new Key\Cr32());

        $node = 'u0';

        $hash->addNode($node);

        $findNode = $hash->lookUp('qii404');

        $this->assertEquals($node, $findNode);
    }

    /**
     * Test addNode();
     */
    public function testaddNodeMd5()
    {
        $hash = new Consistant();
        $hash->setKeyCalculator(new Key\Md5());

        $node = 'u0';

        $hash->addNode($node);

        $findNode = $hash->lookUp('qii404');

        $this->assertEquals($node, $findNode);
    }
}
// end of file Cr32Test.php

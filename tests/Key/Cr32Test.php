<?php

use Redis\Key;

class Cr32Test extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->key = 'qii';
    }

    /**
     * Test calc();
     */
    public function testcalc()
    {
        $keyCalculator = new Key\Cr32();

        $result = $keyCalculator->calc($this->key);

        $this->assertEquals(crc32($this->key), $result);
    }
}

// end of file Cr32Test.php

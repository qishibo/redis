<?php

use Redis\Key;

class Md5Test extends PHPUnit_Framework_TestCase
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
        $keyCalculator = new Key\Md5();

        $result = $keyCalculator->calc($this->key);

        $this->assertEquals(md5($this->key), $result);
    }
}
// end of file Md5Test.php

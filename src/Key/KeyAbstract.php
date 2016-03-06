<?php

namespace Redis\Key;

abstract class KeyAbstract
{
    // calc the key to hash number
    abstract function calc($key);
}

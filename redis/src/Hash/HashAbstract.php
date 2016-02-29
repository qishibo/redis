<?php

namespace Redis\Hash;

abstract class HashAbstract
{
    abstract function addNode($server);
    abstract function lookUp($key);
}

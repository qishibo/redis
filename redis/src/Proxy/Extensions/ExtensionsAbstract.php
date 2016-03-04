<?php

namespace Redis\Proxy\Extensions;

abstract class ExtensionsAbstract
{
    /**
     * use redis extension executing method
     */
    abstract function execute($method, $params);

    /**
     * __destruct for closing redis connection
     */
    abstract function __destruct();
}
// end of file ExtensionsAbstract.php

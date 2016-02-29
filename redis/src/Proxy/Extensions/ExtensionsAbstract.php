<?php

namespace Redis\Proxy\Extensions;

abstract class ExtensionsAbstract
{
    abstract function execute($method, $params);
}

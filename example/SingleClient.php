<?php

use \Redis\SingleClient;
use \Redis\Drivers\RedisFactory;

include '../src/Autoload.php';

$config = ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1];

$redis  = new SingleClient(
    $config,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

$redis->set('name', 'qii404'); // true
$redis->get('name'); // 'qii404'

// end of file SingleClient.php

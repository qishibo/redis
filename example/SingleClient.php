<?php

use Redis\Drivers\RedisFactory;
use Redis\SingleClient;

include '../src/Autoload.php';

$config = ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1, 'auth' => 'qii'];

$redis = new SingleClient(
    $config,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

$redis->set('name', 'qii404'); // true
$r = $redis->get('name'); // 'qii404'

var_dump($r);

// end of file SingleClient.php

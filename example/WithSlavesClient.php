<?php

use Redis\Drivers\RedisFactory;
use Redis\Hash;
use Redis\Key;
use Redis\WithSlavesClient;

include '../src/Autoload.php';

$config = [
    'm' => [
        ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 2],
        ['host' => '127.0.0.1', 'port' => 6380, 'weight' => 1],
    ],
    's' => [
        ['host' => '127.0.0.1', 'port' => 6381],
        ['host' => '127.0.0.1', 'port' => 6382],
    ],
];

$hash = new Hash\Consistant();
$calculator = new Key\Cr32();

$redis = new WithSlavesClient(
    $config,
    $hash,
    $calculator,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

$redis->zadd('key', 99, 'qii404'); // true; executes in master server, such as port 6379
$r = $redis->zscore('key', 'qii404'); // 99; executes in slave server, such as port 6381

var_dump($r);

// end of file WithSlavesClient.php

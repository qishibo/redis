<?php

use Redis\Drivers\RedisFactory;
use Redis\WithoutSlavesClient;
use Redis\Hash;
use Redis\Key;

include '../src/Autoload.php';

$config = [
    ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1],
    ['host' => '127.0.0.1', 'port' => 6380],
];

$hash       = new Hash\Consistant();
$calculator = new Key\Cr32();

$redis = new WithoutSlavesClient(
    $config,
    $hash,
    $calculator,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

$redis->hset('profile', 'name', 'qii44'); // true
$r = $redis->hget('profile', 'name'); // 'qii404'

var_dump($r);

// end of file WithoutSlavesClient.php

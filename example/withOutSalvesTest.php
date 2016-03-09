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

$hash = new Hash\Consistant();
$Calculator = new Key\Cr32();

$redis = new WithoutSlavesClient(
    $config,
    RedisFactory::PHPREDIS,
    $hash,
    $Calculator
);

// var_dump($redis->delete('zhangman'));die;

$field = time() . rand(9, 999);
$value = uniqid('zhangman=');

echo "field is {$field}, value is {$value}\n\n";

var_dump($redis->hset('shibo', $field, $value));

var_dump($redis->hset('shibo1111199', $field, $value));

var_dump($redis->hget('shibo', $field));

var_dump($redis->hget('shibo1111199', $field));

var_dump('=================');

var_dump($redis->set('name', uniqid()));
var_dump($redis->get('name'));
var_dump($redis->set('age', uniqid()));
var_dump($redis->get('age'));

// var_dump($redis);
// var_dump($redis->randomkey());

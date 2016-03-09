<?php

use Redis\Drivers\RedisFactory;
use Redis\WithSlavesClient;
use Redis\Hash;
use Redis\Key;

include '../src/Autoload.php';

$config = [
    'm' =>[
        ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1],
        ['host' => '127.0.0.1', 'port' => 6380],
    ],
    's' =>[
        ['host' => '127.0.0.1', 'port' => 6381],
        ['host' => '127.0.0.1', 'port' => 6382],
    ]
];

// hash stragety, you can also define your stragety in Hash folder
$hash = new Hash\Consistant();

// key hasher, such as new Md5 or Cr32, you can add it in Key folder
$Calculator = new Key\Cr32();
// $Calculator = new Key\Md5();

$redis = new WithSlavesClient(
    $config,
    $hash,
    $Calculator,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

// var_dump($redis->delete('zhangman'));die;

$field = time() . rand(9, 999);
$value = uniqid('zhangman=');

var_dump($field, $value);

$r = $redis->hset('shibo', $field, $value);
var_dump($r);

$r = $redis->hset('shibo1111199', $field, $value);
var_dump($r);

var_dump($redis->hget('shibo', $field));

$r = $redis->hget('shibo1111199', $field);
var_dump($r);

var_dump($redis->set('name', uniqid()));
var_dump($redis->get('name'));
var_dump($redis->set('age', uniqid()));
var_dump($redis->get('age'));

// var_dump($redis);
// var_dump($redis->randomkey());

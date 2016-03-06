<?php

use Redis\Proxy;
use Redis\Client;


include 'Autoload.php';

$config = ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1];

$redis = new Client\SingleClient(
    $config,
    Proxy\RedisFactory::PHPREDIS
);

// var_dump($redis);die;

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

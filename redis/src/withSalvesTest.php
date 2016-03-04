<?php

use Redis\Proxy;
use Redis\Client;
use Redis\Hash;
use Redis\Key;

include 'Autoload.php';

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

$hash = new Hash\Consistant();
$Calculator = new Key\Cr32();

$redis = new Client\WithSlavesClient(
	$config,
	Proxy\RedisFactory::PHPREDIS,
	$hash,
	$Calculator
);


$r = $redis->hgetall('U:2439460763:20160219:HistoryData');
var_dump($r);

$r = $redis->hget('U:2439460763:20160219:HistoryData', 'steps');
var_dump($r);

$r = $redis->get('U:1790635741:BasicInfo');
var_dump($r);

$r = $redis->smembers('M:2316410043:Achieved');
var_dump($r);

var_dump($redis->randomkey());

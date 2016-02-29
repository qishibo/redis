<?php

use Redis\Proxy;
use Redis\Client;
use Redis\Hash;
use Redis\Key;

include 'Autoload.php';

// for secret..
$rawConfig = file('/Users/shibo/redisConfig');
$rawConfig = array_map(function($v){return trim($v);}, $rawConfig);

$config = [
    'm' =>[
        ['host' => $rawConfig[0], 'port' => $rawConfig[1]],
        ['host' => $rawConfig[0], 'port' => $rawConfig[1]],
    ],
    's' =>[
        ['host' => $rawConfig[0], 'port' => $rawConfig[1]],
        ['host' => $rawConfig[0], 'port' => $rawConfig[1]],
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

// var_dump($redis);

$r = $redis->hgetall('U:2439460763:20160219:HistoryData');
var_dump($r);

$r = $redis->hget('U:2439460763:20160219:HistoryData', 'steps');
var_dump($r);

$r = $redis->get('U:1790635741:BasicInfo');
var_dump($r);

$r = $redis->smembers('M:2316410043:Achieved');
var_dump($r);

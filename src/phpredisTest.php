<?php

use Redis\Proxy\Extensions;

include 'Autoload.php';

// for secret..
$rawConfig = file('/Users/shibo/redisConfig');
$rawConfig = array_map(function($v){return trim($v);}, $rawConfig);

$config = ['host' => $rawConfig[0], 'port' => $rawConfig[1]];

$redis = new Extensions\PhpRedis($config);

// var_dump($redis);

$r = $redis->execute('get', 'U:3170428341:BasicInfo');
var_dump(json_decode($r, 1));

$r = $redis->execute('hgetall', 'U:2101958567:20160207:HistoryData');
var_dump($r);

$r = $redis->execute('hget', ['U:2101958567:20160207:HistoryData', 'steps']);
var_dump($r);

// var_dump($r);

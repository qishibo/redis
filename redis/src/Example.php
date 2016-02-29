<?php

use Redis\Key;
use Redis\Proxy;
use Redis\Hash;
use Redis\Client;

include 'Autoload.php';

$config = [
    'master' => [
        [
            'host' => 'http://www.mtest1.com',
            'port' => 6379
        ],
        [
            'host' => 'http://www.mtest2.com',
            'port' => 6379
        ]
    ],
    'slave' => [
        [
            'host' => 'http://www.stest1.com',
            'port' => 6379
        ],
        [
            'host' => 'http://www.stest2.com',
            'port' => 6379
        ]
    ],
];

$redis = new Client\WithSlavesClient();
var_dump($redis);




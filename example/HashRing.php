<?php

use Redis\Key;
use Redis\Hash;

include '../src/Autoload.php';

$hash = new Hash\Consistant();
$calc = new Key\Cr32();
// $calc = new Key\Md5();
// $calc = new Key\Con();
$hash->keyCalculator = $calc;

$copy = 1;

$hash->addNode('u0', $copy);
$hash->addNode('u1', $copy);
$hash->addNode('u2', $copy);
$hash->addNode('u3', $copy);

for ($i=0; $i < 10000; $i++) {
    // $key = 'qii' . $i . chr(mt_rand(0, 97));
    $key = 'qii' . $i;
    $node = $hash->lookUp($key);
    $c[$node]++;
    // var_dump($node);
}
var_dump($c);


// var_dump($hash, $node);

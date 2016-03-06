# Redis
Redis client for php, which supports single redis server, or redis Master-Slave clusters.


## Example

1.**single redis server **

read & write operations are all executed in the single serve.

```php
use Redis\Proxy;
use Redis\Client;

include 'Autoload.php';

$config = ['host' => '127.0.0.1', 'port' => 6379];

$redis = new Client\SingleClient(
    $config,
    Proxy\RedisFactory::PHPREDIS
);

$redis->set('name', 'qii404'); // true
$redis->get('name'); // 'qii404'
```

2.**redis cluster without slaves**

read & write operations executed in the same one server of the cluster.

```php
use Redis\Proxy;
use Redis\Client;
use Redis\Hash;
use Redis\Key;

include 'Autoload.php';

$config = [
    ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1],
    ['host' => '127.0.0.1', 'port' => 6380, 'weight' => 2],
];

$hash = new Hash\Consistant();
$Calculator = new Key\Cr32();

$redis = new Client\WithoutSlavesClient(
    $config,
    Proxy\RedisFactory::PHPREDIS,
    $hash,
    $Calculator
);

// when using the same key, both read & write operation executed in the same server, such as port 6379
$redis->hset('profile', 'name', 'qii44'); // true
$redis->hget('profile', 'name'); // 'qii404'
```

3.**redis cluster with slaves**

read & write operations executed in the different servers, read from the slave servers, write from the master servers

(*You should config it right for 'm' & 's', such as 6381 is slave of 6379, 6382 is slave of 6380*).

```php
use Redis\Proxy;
use Redis\Client;
use Redis\Hash;
use Redis\Key;

include 'Autoload.php';

$config = [
    'm' =>[
        ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 2],
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

$redis->zadd('key', 99, 'qii404'); // true; executes in master server, such as port 6379
$redis->zscore('key', 'qii404'); // 99; executes in slave server, such as port 6381
```

## Attentions

- When you use the 'weight' in the config, it works only in the cluster mode, and when in the Master-Slave mode, you should config in the 'm' arrays but not 's' arrays.
- The different clients are implemented by polymorphism, so it is simple and efficient, but you need to new a client yourself.
- If you have any questions, do not ask me.

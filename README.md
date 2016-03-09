# Redis
Redis client for php, which supports single redis server, or redis Master-Slave clusters.

*building...*


## Example

1.**single redis server**

read & write operations are all executed in the single serve.

```php
use \Redis\SingleClient;
use \Redis\Drivers\RedisFactory;

include 'Autoload.php';

$config = ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1];

$redis = new SingleClient(
    $config,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

$redis->set('name', 'qii404'); // true
$redis->get('name'); // 'qii404'
```

2.**redis cluster without slaves**

read & write operations executed in the same one server of the cluster.

```php
use Redis\Drivers\RedisFactory;
use Redis\WithoutSlavesClient;
use Redis\Hash;
use Redis\Key;

include 'Autoload.php';

$config = [
    ['host' => '127.0.0.1', 'port' => 6379, 'weight' => 1],
    ['host' => '127.0.0.1', 'port' => 6380],
];

// hash stragety, you can also define your stragety in Hash folder
$hash = new Hash\Consistant();

// key hasher, such as new Md5 or Cr32, you can add it in Key folder
$Calculator = new Key\Cr32();
// $Calculator = new Key\Md5();

$redis = new WithoutSlavesClient(
    $config,
    $hash,
    $Calculator,
    RedisFactory::PHPREDIS // this is optional param, default is PHPREDIS driver
);

// when using the same key, both read & write operation executed in the same server, such as port 6379
$redis->hset('profile', 'name', 'qii44'); // true
$redis->hget('profile', 'name'); // 'qii404'
```

3.**redis cluster with slaves**

read & write operations executed in the different servers, read from the slave servers, write from the master servers

(*You should config it right for 'm' & 's', such as 6381 is slave of 6379, 6382 is slave of 6380*).

```php
use Redis\Drivers\RedisFactory;
use Redis\WithSlavesClient;
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

$redis->zadd('key', 99, 'qii404'); // true; executes in master server, such as port 6379
$redis->zscore('key', 'qii404'); // 99; executes in slave server, such as port 6381
```

## Attentions

- When you use the 'weight' in the config, it works only in the cluster mode, and when in the Master-Slave mode, you should config in the 'm' arrays but not 's' arrays.
- The different clients are implemented by polymorphism, so it is simple and efficient, but you need to new a client yourself.
- If you have any questions, do not ask me.

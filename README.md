# Redis

Redis client for php, which supports single redis server, or redis Master-Slave clusters.

## Example

1.**single redis server **

read &amp; write operations are all executed in the single serve.

    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Proxy</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Client</span>;

    <span class="hljs-keyword">include</span> <span class="hljs-string">'Autoload.php'</span>;

    <span class="hljs-variable">$config</span> = [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6379</span>];

    <span class="hljs-variable">$redis</span> = <span class="hljs-keyword">new</span> Client\SingleClient(
        <span class="hljs-variable">$config</span>,
        Proxy\RedisFactory::PHPREDIS
    );

    <span class="hljs-variable">$redis</span>->set(<span class="hljs-string">'name'</span>, <span class="hljs-string">'qii404'</span>); <span class="hljs-comment">// true</span>
    <span class="hljs-variable">$redis</span>->get(<span class="hljs-string">'name'</span>); <span class="hljs-comment">// 'qii404'</span>
    `</pre>

    2.**redis cluster without slaves**

    read & write operations executed in the same one server of the cluster.

    <pre>`<span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Proxy</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Client</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Hash</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Key</span>;

    <span class="hljs-keyword">include</span> <span class="hljs-string">'Autoload.php'</span>;

    <span class="hljs-variable">$config</span> = [
        [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6379</span>, <span class="hljs-string">'weight'</span> => <span class="hljs-number">1</span>],
        [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6380</span>, <span class="hljs-string">'weight'</span> => <span class="hljs-number">2</span>],
    ];

    <span class="hljs-variable">$hash</span> = <span class="hljs-keyword">new</span> Hash\Consistant();
    <span class="hljs-variable">$Calculator</span> = <span class="hljs-keyword">new</span> Key\Cr32();

    <span class="hljs-variable">$redis</span> = <span class="hljs-keyword">new</span> Client\WithoutSlavesClient(
        <span class="hljs-variable">$config</span>,
        Proxy\RedisFactory::PHPREDIS,
        <span class="hljs-variable">$hash</span>,
        <span class="hljs-variable">$Calculator</span>
    );

    <span class="hljs-comment">// when using the same key, both read & write operation executed in the same server, such as port 6379 </span>
    <span class="hljs-variable">$redis</span>->hset(<span class="hljs-string">'profile'</span>, <span class="hljs-string">'name'</span>, <span class="hljs-string">'qii44'</span>); <span class="hljs-comment">// true</span>
    <span class="hljs-variable">$redis</span>->hget(<span class="hljs-string">'profile'</span>, <span class="hljs-string">'name'</span>); <span class="hljs-comment">// 'qii404'</span>
    `</pre>

    3.**redis cluster with slaves**

    read & write operations executed in the different servers, read from the slave servers, write from the master servers

    (_You should config it right for 'm' & 's', such as 6381 is slave of 6379, 6382 is slave of 6380_).

    <pre>`<span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Proxy</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Client</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Hash</span>;
    <span class="hljs-keyword">use</span> <span class="hljs-title">Redis</span>\<span class="hljs-title">Key</span>;

    <span class="hljs-keyword">include</span> <span class="hljs-string">'Autoload.php'</span>;

    <span class="hljs-variable">$config</span> = [
        <span class="hljs-string">'m'</span> =>[
            [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6379</span>, <span class="hljs-string">'weight'</span> => <span class="hljs-number">2</span>],
            [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6380</span>],
        ],
        <span class="hljs-string">'s'</span> =>[
            [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6381</span>],
            [<span class="hljs-string">'host'</span> => <span class="hljs-string">'127.0.0.1'</span>, <span class="hljs-string">'port'</span> => <span class="hljs-number">6382</span>],
        ]
    ];

    <span class="hljs-variable">$hash</span> = <span class="hljs-keyword">new</span> Hash\Consistant();
    <span class="hljs-variable">$Calculator</span> = <span class="hljs-keyword">new</span> Key\Cr32();

    <span class="hljs-variable">$redis</span> = <span class="hljs-keyword">new</span> Client\WithSlavesClient(
        <span class="hljs-variable">$config</span>,
        Proxy\RedisFactory::PHPREDIS,
        <span class="hljs-variable">$hash</span>,
        <span class="hljs-variable">$Calculator</span>
    );

    <span class="hljs-variable">$redis</span>->zadd(<span class="hljs-string">'key'</span>, <span class="hljs-number">99</span>, <span class="hljs-string">'qii404'</span>); <span class="hljs-comment">// true; executes in master server, such as port 6379</span>
    <span class="hljs-variable">$redis</span>->zscore(<span class="hljs-string">'key'</span>, <span class="hljs-string">'qii404'</span>); <span class="hljs-comment">// 99; executes in slave server, such as port 6381</span>

## Attentions

*   When you use the 'weight' in the config, it works only in the cluster mode, and when in the Master-Slave mode, you should config in the 'm' arrays but not 's' arrays.
*   The different clients are implemented by polymorphism, so it is simple and efficient, but you need to new a client yourself.
*   If you have any questions, do not ask me.
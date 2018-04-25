<?php

// namespace Redis\Drivers\Extensions;

class PhpRedisTest extends PHPUnit_Framework_TestCase
{
    /*public function __construct(array $config)
    {
        $this->config = $config;

        try {
            $this->redis  = new \Redis();
        }
        catch (\Exception $e) {
            exit($e->getMessage() . ' Please check your phpredis extension...');
        }

        try {
            $this->redis->connect($config['host'], $config['port']);
        }
        catch (\Exception $e) {
            exit($e->getMessage() . ' Cant\'t create connection with your phpredis extension...');
        }
    }*/

    public function testexecute()
    {
        // $redis = Mockery::mock(Redis::class);
        /*$redis->shouldReceive('get')->andReturn(function () {
            return func_get_args();
        });

        $phpRedis = new PhpRedis();
        $result = $phpRedis->execute('get', ['key']);

        var_dump($redis->get());
        // var_dump($redis);
        die;

        $http->shouldReceive('execute')->andReturnUsing(function ($url, $method, $body) {
                        return compact('url', 'method', 'body');
                    });*/
        // !is_array($params) && $params = (array) $params;

        // return call_user_func_array([$this->redis, $method], $params);
    }

    /*    public function __destruct()
        {
            $this->redis->close();
        }*/
}

// end of file PhpRedis.php

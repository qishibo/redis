<?php

namespace Redis\Proxy\Extensions;

class PhpRedis extends ExtensionsAbstract
{
    public function __construct(array $config)
    {
        //var_dump($config);
        $this->config = $config;
        try {
            $this->redis  = new \Redis();
        } catch (Exception $e) {
            die('no phpredis extension...');
        }
        // var_dump($config['host'], $config['port']);die;
        try{
            $this->redis->connect($config['host'], $config['port']);
        }catch (Exception $e){
            var_dump($e->getMessage());
        }
    }

    public function execute($method, $params)
    {
        // var_dump($this->redis);
        // var_dump($method, $params);die;
        !is_array($params) && $params = (array) $params;
        // var_dump($this->config, $method, $params[0], $this->redis->$method($params[0]));die;
        return call_user_func_array([$this->redis, $method], $params);
        // return $re;
        // var_dump($re);die;
    }

    public function __destruct()
    {
        $this->redis->close();
    }
}
// end of file PhpRedis.php

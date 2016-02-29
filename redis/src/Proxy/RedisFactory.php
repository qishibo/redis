<?php

namespace Redis\Proxy;

class RedisFactory
{
    const PHPREDIS = 'PhpRedis';
    const PREDIS   = 'PRedis';

    /**
     * [getRedis description]
     *
     * @param  array  $config [description]
     * @param  string $type   default is phpredis
     *
     * @return \Proxy\ExtensionProxyAbstract         [description]
     */
    public static function getRedis(array $config, $type = self::PHPREDIS)
    {
        // var_dump($config, $type);die;
        switch ($type) {
            case self::PHPREDIS:
            case self::PREDIS:
                //there is a keng here, when use string in new class,the name path must be full
                $redisClassName = '\\Redis\\Proxy\\Extensions\\' . $type;
                // var_dump($redisClassName);die;
                $redis = new $redisClassName($config);

                break;

            default:
                throw new Expection('undefined redis type');
        }

        return $redis;
    }
}
// end of file RedisFactory.php

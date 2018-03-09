<?php

namespace Redis\Drivers;

class RedisFactory
{
    /**
     * phpredis extension driver
     */
    const PHPREDIS = 'PhpRedis';

    /**
     * predis driver
     */
    const PREDIS   = 'PRedis';

    /**
     * get redis instance
     *
     * @param  array  $config [description]
     * @param  string $type   default is phpredis
     *
     * @return \Redis\Drivers\DriversInterface         [description]
     * @throws \Exception
     */
    public static function getRedis(array $config, $type = self::PHPREDIS)
    {
        switch ($type) {
            case self::PHPREDIS:
            case self::PREDIS:
                $redisClassName = '\\Redis\\Drivers\\' . $type;
                $redis = new $redisClassName($config);

                break;

            default:
                throw new \Exception('undefined redis extensions type, you can add it if you got another php extendion, folder is ' . __DIR__);
        }

        return $redis;
    }
}

// end of file RedisFactory.php

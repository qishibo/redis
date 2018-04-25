<?php

namespace Redis\Drivers;

class RedisFactory
{
    const PHPREDIS = 'PhpRedis';
    const PREDIS = 'PRedis';

    /**
     * [getRedis description].
     *
     * @param array  $config [description]
     * @param string $type   default is phpredis
     *
     * @return \Drivers\ExtensionDriversAbstract [description]
     */
    public static function getRedis(array $config, $type = self::PHPREDIS)
    {
        switch ($type) {
            case self::PHPREDIS:
            case self::PREDIS:
                //there is a keng here, when use string in new class,the namespace must be full
                $redisClassName = '\\Redis\\Drivers\\'.$type;

                $redis = new $redisClassName($config);

                break;

            default:
                throw new \Exception('undefined redis extensions type, you can add it if you got another php extendion, folder is '.__DIR__);
        }

        return $redis;
    }
}

// end of file RedisFactory.php

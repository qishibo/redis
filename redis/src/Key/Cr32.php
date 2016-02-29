<?php

namespace Redis\Key;

class Cr32 extends KeyAbstract
{
    /**
     * calc the key to hash number
     *
     * @param  string $key redis key
     *
     * @return int      hash number
     */
    public function calc($key)
    {
        return crc32($key);
    }
}

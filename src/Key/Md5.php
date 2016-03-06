<?php

namespace Redis\Key;

class Md5 extends KeyAbstract
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
        return md5($key);
        // return substr(md5($key), 0, 8);
    }
}

<?php

namespace Redis\Key;

class Con extends KeyAbstract
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
        $md5 = md5($key); // 8 hexits = 32bit

        $md5 = pack("H*", $md5);
        $hash = 0;
        for ($i = 0; $i < 4; $i++) {
            $hash += ((ord($md5[$i * 4 + 3]) & 0xFF) << 24) | ((ord($md5[$i * 4 + 2]) & 0xFF) << 16) | ((ord($md5[$i * 4 + 1]) & 0xFF) << 8) | ((ord($md5[$i * 4 + 0]) & 0xFF));
        }

        return $hash;

    }
}

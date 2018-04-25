<?php

namespace Redis\Key;

class Cr32 implements KeyInterface
{
    /**
     * calc the key to hash number.
     *
     * @param string $key redis key
     *
     * @return int hash number
     */
    public function calc($key)
    {
        return crc32($key);
    }
}

// end of file Cr32.php

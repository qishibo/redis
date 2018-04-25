<?php

namespace Redis\Key;

interface KeyInterface
{
    /**
     * calc the key to hash number.
     */
    public function calc($key);
}

// end of file KeyInterface.php

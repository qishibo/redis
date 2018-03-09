<?php

namespace Redis\Drivers;

interface DriversInterface
{
    /**
     * use redis extension executing method
     *
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public function execute($method, $params);

    /**
     * __destruct for closing redis connection
     */
    public function __destruct();
}

// end of file DriversInterface.php

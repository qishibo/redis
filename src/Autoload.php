<?php

namespace Redis;

class AutoLoader
{
    public function __construct($baseDir = __DIR__)
    {
        $this->baseDir         = $baseDir;
        $this->nameSpacePrefix = __NAMESPACE__ . "\\";
        $this->prefixLength    = strlen($this->nameSpacePrefix);
    }

    public function register()
    {
        spl_autoload_register(function ($className)
        {
            // autoload only this library's files
            if (0 === strpos($className, $this->nameSpacePrefix)) {

                $file = $this->baseDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, explode("\\", substr($className, $this->prefixLength))) . '.php';

                if (is_file($file)) {
                    require_once $file;
                }
            }
        });
    }
}

(new AutoLoader())->register();

<?php

namespace Yard\IRMA;

class Autoloader
{

    /**
     * Autoloader constructor.
     * PSR autoloader
     */
    public function __construct()
    {
        spl_autoload_register(function ($className) {
            $baseDir   = __DIR__ . '/src/IRMA/';
            $namespace = str_replace("\\", "/", __NAMESPACE__);
            $className = str_replace("\\", "/", $className);
            $class     = $baseDir . (empty($namespace) ? "" : $namespace . "/") . $className . '.php';
            $class     = str_replace('/Yard/IRMA/Yard/IRMA/', '/', $class);
            if (file_exists($class)) {
                require_once($class);
            }
        });
    }
}

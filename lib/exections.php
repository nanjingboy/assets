<?php
namespace Assets;

use Exception;

class AssetsException extends Exception
{
}

class ShellException extends AssetsException
{
}

class ConfigException extends AssetsException
{
}

class UndefinedMethodException extends AssetsException
{
    public function __construct($class, $method)
    {
        parent::__construct("Call to undefined method {$class}::{$method}()");
    }
}
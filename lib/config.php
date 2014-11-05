<?php
namespace Assets;

use AssetLoader;

final class Config
{
    private static $_config;

    public static function init($configFilePath)
    {
        $configFilePath = realpath($configFilePath);
        if (!file_exists($configFilePath)) {
            throw new ConfigException("can't load config from {$configFilePath}");
        }

        $config = require($configFilePath);
        $serverRoot = rtrim(realpath($config['path']['serverRoot']), DIRECTORY_SEPARATOR);
        if ($serverRoot === false) {
            throw new AssetsException('Error config for server root path.');
        }

        foreach ($config['path'] as $key => $path) {
            if ($key === 'serverRoot') {
                $path = $serverRoot;
            } else {
                $path = $serverRoot . DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
            }
            $config['path'][$key] = $path;
        }

        AssetLoader::init(
            $config['path']['serverRoot'],
            $config['path']['javascripts'],
            $config['path']['stylesheets']
        );

        self::$_config = $config;
    }

    public static function getPrecompile()
    {
        return (empty(self::$_config['precompile']) ? array() : self::$_config['precompile']);
    }

    public static function isPrecompileable()
    {
        return !empty(static::getPrecompile());
    }

    public static function __callStatic($method, $arguments)
    {
        $matches = array();
        preg_match_all('/^get(([A-Z][a-z]+)+)Path$/', $method, $matches);
        if (empty($matches[1][0])) {
            throw new UndefinedMethodException(__CLASS__, $method);
        }

        $key = lcfirst($matches[1][0]);
        if (array_key_exists($key, self::$_config['path'])) {
            return self::$_config['path'][$key];
        }

        throw new UndefinedMethodException(__CLASS__, $method);
    }
}

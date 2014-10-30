<?php
namespace Assets;

final class Config
{
    private static $_serverRootPath;

    private static $_jsDirectoryPath;
    private static $_cssDirectoryPath;

    private static $_compiledJsDirectoryPath;
    private static $_compiledCssDirectoryPath;


    public static function init($serverRootPath,
        $jsDirectoryPath, $cssDirectoryPath,
        $compiledJsDirectoryPath, $compiledCssDirectoryPath)
    {
        self::$_serverRootPath = realpath($serverRootPath);

        self::$_jsDirectoryPath = realpath($jsDirectoryPath);
        self::$_cssDirectoryPath = realpath($cssDirectoryPath);


        if (file_exists($compiledJsDirectoryPath) === false) {
            mkdir($compiledJsDirectoryPath, 0770, true);
        }
        if (file_exists($compiledCssDirectoryPath) === false) {
            mkdir($compiledCssDirectoryPath, 0770, true);
        }
        self::$_compiledJsDirectoryPath = realpath($compiledJsDirectoryPath);
        self::$_compiledCssDirectoryPath = realpath($compiledCssDirectoryPath);
    }

    public static function __callStatic($method, $arguments)
    {
        $property = '_' . lcfirst(str_replace('get', '', $method));
        return self::$$property;
    }
}

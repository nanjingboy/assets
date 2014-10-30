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

        self::$_serverRootPath = rtrim($serverRootPath, DIRECTORY_SEPARATOR);
        self::$_jsDirectoryPath = rtrim($jsDirectoryPath, DIRECTORY_SEPARATOR);
        self::$_cssDirectoryPath = rtrim($cssDirectoryPath, DIRECTORY_SEPARATOR);
        self::$_compiledJsDirectoryPath = rtrim(
            $compiledJsDirectoryPath, DIRECTORY_SEPARATOR
        );
        self::$_compiledCssDirectoryPath = rtrim(
            $compiledCssDirectoryPath, DIRECTORY_SEPARATOR
        );
    }

    public static function __callStatic($method, $arguments)
    {
        $property = '_' . lcfirst(str_replace('get', '', $method));
        return self::$$property;
    }
}

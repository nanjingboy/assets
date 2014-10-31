<?php
namespace Assets;

final class Config
{
    private static $_serverRootPath;
    private static $_jsDirectoryPath;
    private static $_cssDirectoryPath;

    public static function init($serverRootPath, $jsDirectoryPath, $cssDirectoryPath)
    {
        self::$_serverRootPath = rtrim($serverRootPath, DIRECTORY_SEPARATOR);
        self::$_jsDirectoryPath = rtrim($jsDirectoryPath, DIRECTORY_SEPARATOR);
        self::$_cssDirectoryPath = rtrim($cssDirectoryPath, DIRECTORY_SEPARATOR);
    }

    public static function getServerRootPath()
    {
        return self::$_serverRootPath;
    }

    public static function getJsDirectoryPath()
    {
        return self::$_jsDirectoryPath;
    }

    public static function getCssDirectoryPath()
    {
        return self::$_cssDirectoryPath;
    }
}

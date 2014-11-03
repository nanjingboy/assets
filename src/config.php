<?php
namespace Assets;

use AssetLoader;

final class Config
{
    private static $_serverRootPath;
    private static $_jsDirectoryPath;
    private static $_cssDirectoryPath;
    private static $_imageDirectoryPath;
    private static $_fontDirectoryPath;

    public static function init($serverRootPath,
        $jsDirectoryPath, $cssDirectoryPath,
        $imageDirectoryPath, $fontDirectoryPath)
    {
        self::$_serverRootPath = rtrim($serverRootPath, DIRECTORY_SEPARATOR);
        self::$_jsDirectoryPath = rtrim($jsDirectoryPath, DIRECTORY_SEPARATOR);
        self::$_cssDirectoryPath = rtrim($cssDirectoryPath, DIRECTORY_SEPARATOR);
        self::$_imageDirectoryPath = rtrim($imageDirectoryPath, DIRECTORY_SEPARATOR);
        self::$_fontDirectoryPath = rtrim($fontDirectoryPath, DIRECTORY_SEPARATOR);

        AssetLoader::init(self::$_serverRootPath, self::$_jsDirectoryPath, self::$_cssDirectoryPath);
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

    public static function getImageDirectoryPath()
    {
        return self::$_imageDirectoryPath;
    }

    public static function getFontDirectoryPath()
    {
        return self::$_fontDirectoryPath;
    }
}

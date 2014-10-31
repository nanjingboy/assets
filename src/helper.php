<?php
namespace Assets;

class Helper
{
    private static function _assetUrl($baseDir, $url)
    {
        if (preg_match('/^https:|http:|data:/i', $url)) {
            return $url;
        }

        return str_replace(
            Config::getServerRootPath(),
            '',
            $baseDir . DIRECTORY_SEPARATOR . ltrim($url, DIRECTORY_SEPARATOR)
        );
    }

    public static function imageUrl($url)
    {
        return self::_assetUrl(Config::getImageDirectoryPath(), $url);
    }

    public static function fontUrl($url)
    {
        return self::_assetUrl(Config::getFontDirectoryPath(), $url);
    }
}
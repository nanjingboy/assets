<?php
namespace Assets;

class Helper
{
    public static function assetUrl($url, $baseDir)
    {
        if (preg_match('/^https:|http:|data:/i', $url)) {
            return $url;
        }

        $serverRootPath = Config::getServerRootPath();
        $url = $baseDir . DIRECTORY_SEPARATOR . ltrim($url, DIRECTORY_SEPARATOR);
        if (strpos($url, $serverRootPath) === 0) {
            return str_replace($serverRootPath, '', $url);
        }
        return false;
    }

    public static function imageUrl($url)
    {
        return self::assetUrl($url, Config::getImagesPath());
    }

    public static function fontUrl($url)
    {
        return self::assetUrl($url, Config::getFontsPath());
    }
}
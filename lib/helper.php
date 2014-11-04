<?php
namespace Assets;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

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

    /**
     * remove file or directory(with recursive)
     */
    public static function removePath($path)
    {
        if (is_dir($path)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..'))) {
                    continue;
                }
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }

            return rmdir($path);
        }

        return (file_exists($path) ? unlink($path) : true);
    }
}
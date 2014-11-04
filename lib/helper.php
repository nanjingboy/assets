<?php
namespace Assets;

use Assets\Uglify\Js;
use Assets\Uglify\Css;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Helper
{
    public static function includeAsset($file, $type)
    {
        if ($type !== 'js' && $type !== 'css') {
            return array();
        }

        $serverRootPath = Config::getServerRootPath();
        $file = ltrim($file, DIRECTORY_SEPARATOR) . '.' . $type;

        if (Config::isPrecompileEnable()) {
            $compiledCacheFile = $serverRootPath . DIRECTORY_SEPARATOR . '.assetsrc';
            if (file_exists($compiledCacheFile)) {
                $compiledCaches = unserialize(file_get_contents($compiledCacheFile));
                if (!empty($compiledCaches[$file])) {
                    return array($compiledCaches[$file]);
                }
            }

            if ($type === 'js') {
                $distFile = Js::uglify($file);
            } else {
                $distFile = Css::uglify($file);
            }
            return ($distFile === false ? array() : array($distFile));
        }

        if ($type === 'js') {
            return Js::loadSrcFiles($file);
        } else {
            return Css::loadSrcFiles($file);
        }
    }

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
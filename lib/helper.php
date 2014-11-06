<?php
namespace Assets;

use AssetLoader;
use SplFileInfo;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Helper
{
    public static function includeAsset($file, $type)
    {
        if ($type !== 'js' && $type !== 'css') {
            return array();
        }

        if (preg_match('/^https:|http:/i', $file)) {
            return array($file);
        }

        $serverRootPath = Config::getServerRootPath();
        $file = ltrim($file, DIRECTORY_SEPARATOR) . '.' . $type;
        if (Config::isPrecompileable()) {
            $compiledCacheFile = $serverRootPath . DIRECTORY_SEPARATOR . '.assetsrc';
            if (file_exists($compiledCacheFile)) {
                $compiledCaches = unserialize(file_get_contents($compiledCacheFile));
                if (!empty($compiledCaches[$file])) {
                    return array($compiledCaches[$file]);
                }
            }

            $uglifyClass = '\\Assets\\Uglify\\' . ucfirst($type);
            $distFile = $uglifyClass::uglify($file);
            return ($distFile === false ? array() : array($distFile));
        }

        return static::loadCompiledFiles($file, $type);
    }

    public static function loadCompiledFiles($file, $type)
    {
        if ($type !== 'js' && $type !== 'css') {
            return array();
        }

        if ($type === 'js') {
            $compilers = array('Coffee');
            $files = AssetLoader::loadJs($file);
        } else if ($type === 'css') {
            $compilers = array('Css', 'Scss', 'Less');
            $files = AssetLoader::loadCss($file);
        }

        $serverRootPath = Config::getServerRootPath();
        foreach ($files as $index => $file) {
            $fileInfo = new SplFileInfo("{$serverRootPath}{$file}");
            $compiler = ucfirst($fileInfo->getExtension());
            if (in_array($compiler, $compilers)) {
                $compiler = "\\Assets\\Compiler\\{$compiler}";
                $files[$index] = $compiler::compile($fileInfo->getPathname());
            }
        }

        return $files;
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

    public static function emptyDirectory($path)
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
            return true;
        }

        return false;
    }
}
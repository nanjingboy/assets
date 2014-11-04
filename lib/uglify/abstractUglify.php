<?php
namespace Assets\Uglify;

use SplFileInfo;
use AssetLoader;
use Assets\Config;

abstract class AbstractUglify
{
    use \Assets\Concerns\Path;

    protected static $_compiledDirName = 'uglified';

    public static function loadSrcFiles($file)
    {
        if (static::$_type === 'js') {
            $files = AssetLoader::loadJs($file);
        } else {
            $files = AssetLoader::loadCss($file);
        }

        $serverRootPath = Config::getServerRootPath();
        foreach ($files as $index => $file) {
            $fileInfo = new SplFileInfo("{$serverRootPath}{$file}");
            $compiler = ucfirst($fileInfo->getExtension());
            if (in_array($compiler, static::$_compilers)) {
                $compiler = "\\Assets\\Compiler\\{$compiler}";
                $files[$index] = $compiler::compile($fileInfo->getPathname());
            }
        }

        return $files;
    }

    public static function uglify($file)
    {
        $file = str_replace(self::_getDirectory(), '', $file);
        $srcFiles = static::loadSrcFiles($file);
        if (empty($srcFiles)) {
            return false;
        }

        $hashes = array();
        $serverRootPath = Config::getServerRootPath();
        foreach ($srcFiles as $index => $srcFile) {
            $srcFile = "{$serverRootPath}{$srcFile}";
            $srcFiles[$index] = $srcFile;
            array_push($hashes, md5(filemtime($srcFile)));
        }

        $distFilePath = self::_getDistFilePath($file, implode('', $hashes));
        if (file_exists($distFilePath) === false) {
            $distFile = new SplFileInfo($distFilePath);
            if (file_exists($distFile->getPath()) === false) {
                mkdir($distFile->getPath(), 0774, true);
            }

            if (static::_uglify($srcFiles, $distFilePath) === false) {
                return false;
            }
        }

        return str_replace(Config::getServerRootPath(), '', $distFilePath);
    }
}
<?php
namespace Assets\Compiler;

use SplFileInfo;
use Assets\Config;

abstract class AbstractCompiler
{
    protected static $_type;

    private static function _getDirectory()
    {
        if (static::$_type === 'js') {
            return Config::getJsDirectoryPath() . DIRECTORY_SEPARATOR;
        }

        return Config::getCssDirectoryPath() . DIRECTORY_SEPARATOR;
    }

    private static function _getCompiledDirectory()
    {
        $serverRootPath = rtrim(Config::getServerRootPath(), DIRECTORY_SEPARATOR);
        $directory =  str_replace($serverRootPath, '', self::_getDirectory());
        return $serverRootPath . DIRECTORY_SEPARATOR . 'tmp' . $directory;
    }

    private static function _getDistFile($srcFile)
    {
        $srcFilePath = trim(
            str_replace(self::_getDirectory(), '', $srcFile->getPath() . DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR
        );
        if (!empty($srcFilePath)) {
            $srcFilePath = $srcFilePath . DIRECTORY_SEPARATOR;
        }

        $distFileName = $srcFile->getBasename(
            '.' . $srcFile->getExtension()
        ) . '_' . md5($srcFile->getMTime()) . '.' . static::$_type;

        return new SplFileInfo(
            static::_getCompiledDirectory() . $srcFilePath . $distFileName
        );
    }

    public static function compile($file)
    {
        $baseDir = self::_getDirectory();
        if (strpos($file, $baseDir) !== 0) {
            $file = $baseDir . ltrim($file, DIRECTORY_SEPARATOR);
        }

        $srcFile = new SplFileInfo($file);
        if (file_exists($srcFile) === false) {
            return false;
        }

        $distFile = self::_getDistFile($srcFile);
        if (file_exists($distFile) === false) {
            $distFilePath = $distFile->getPath();
            if (file_exists($distFilePath) === false) {
                mkdir($distFilePath, 0774, true);
            }
            if (static::_compile($srcFile, $distFile) === false) {
                return false;
            }
        }
        return str_replace(Config::getServerRootPath(), '', $distFile->getPathname());
    }
}
<?php
namespace Assets\Compiler;

use SplFileInfo;
use Assets\Config;

abstract class AbstractCompiler
{
    protected static $_type;

    abstract protected function _compile($srcFile, $distFile);

    private static function _getDirectoryPath()
    {
        if (static::$_type === 'js') {
            return Config::getJsDirectoryPath() . DIRECTORY_SEPARATOR;
        }

        return Config::getCssDirectoryPath() . DIRECTORY_SEPARATOR;
    }

    private static function _getCompiledDirectoryPath()
    {
        if (static::$_type === 'js') {
            return Config::getCompiledJsDirectoryPath() . DIRECTORY_SEPARATOR;
        }

        return Config::getCompiledCssDirectoryPath() . DIRECTORY_SEPARATOR;
    }

    private static function _getDistFile($srcFile)
    {
        $srcFilePath = trim(
            str_replace(self::_getDirectoryPath(), '', $srcFile->getPath()),
            DIRECTORY_SEPARATOR
        ) . DIRECTORY_SEPARATOR;
        $distFileName = $srcFile->getBasename(
            '.' . $srcFile->getExtension()
        ) . '_' . md5($srcFile->getMTime()) . '.' . static::$_type;

        return new SplFileInfo(
            static::_getCompiledDirectoryPath() . $srcFilePath . $distFileName
        );
    }

    public static function compile($file)
    {
        $srcFile = new SplFileInfo(
            self::_getDirectoryPath() . ltrim($file, DIRECTORY_SEPARATOR)
        );
        if (file_exists($srcFile) === false) {
            return false;
        }

        $distFile = self::_getDistFile($srcFile);
        if (file_exists($distFile) === false) {
            $distFilePath = $distFile->getPath();
            if (file_exists($distFilePath) === false) {
                mkdir($distFilePath, 0770, true);
            }
            if (static::_compile($srcFile, $distFile) === false) {
                return false;
            }
        }
        return str_replace(Config::getServerRootPath(), '', $distFile->getPathname());
    }
}
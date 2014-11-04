<?php
namespace Assets\Concerns;

use Assets\Config;

trait Path
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
        return $serverRootPath . DIRECTORY_SEPARATOR . static::$_compiledDirName . $directory;
    }

    private static function _getDistFilePath($srcFile, $lastModified)
    {
        $distFile = trim(str_replace(static::_getDirectory(), '', $srcFile), DIRECTORY_SEPARATOR);
        $fileNameWithoutExtension = implode('.', explode('.', $distFile, -1));
        if (empty($fileNameWithoutExtension)) {
            $fileNameWithoutExtension = $distFile;
        }

        return static::_getCompiledDirectory() . $fileNameWithoutExtension . '_' .
            md5($lastModified) . '.' . static::$_type;
    }
}
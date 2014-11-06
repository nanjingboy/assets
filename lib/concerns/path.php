<?php
namespace Assets\Concerns;

use SplFileInfo;
use Assets\Config;

trait Path
{
    protected static $_type;

    private static function _getBaseDir()
    {
        if (static::$_type === 'js') {
            return Config::getJavascriptsPath() . DIRECTORY_SEPARATOR;
        }
        return Config::getStylesheetsPath() . DIRECTORY_SEPARATOR;
    }

    private static function _getCompiledDir()
    {
        return Config::getServerRootPath() . DIRECTORY_SEPARATOR .
            static::$_compiledDir . DIRECTORY_SEPARATOR . 'assets';
    }

    private static function _getDistFilePath($srcFile, $lastModified)
    {
        $distFile = new SplFileInfo(
            trim(
                str_replace(
                    self::_getBaseDir(),
                    '',
                    $srcFile
                ),
                DIRECTORY_SEPARATOR
            )
        );

        $path = str_replace(DIRECTORY_SEPARATOR, '_', $distFile->getPath());
        $baseName = $distFile->getBasename('.' . $distFile->getExtension());
        if (!empty($path)) {
            $distFile = $path . '_' . $baseName;
        } else {
            $distFile = $baseName;
        }
        $distFile = $distFile . '_' . md5($lastModified) . '.' . static::$_type;

        return self::_getCompiledDir() . DIRECTORY_SEPARATOR . $distFile;
    }
}
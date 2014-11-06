<?php
namespace Assets\Compiler;

use SplFileInfo;
use Assets\Config;

abstract class AbstractCompiler
{
    use \Assets\Concerns\Path;

    protected static $_compiledDir = 'tmp';

    public static function compile($file)
    {
        $baseDir = self::_getBaseDir();
        $file = $baseDir . str_replace($baseDir, '', $file);
        if (file_exists($file) === false) {
            return false;
        }

        $srcFile = new SplFileInfo($file);
        $distFile = new SplFileInfo(
            self::_getDistFilePath($srcFile->getPathname(), $srcFile->getMTime())
        );
        if (file_exists($distFile) === false) {
            if (file_exists($distFile->getPath()) === false) {
                mkdir($distFile->getPath(), 0775, true);
            }
            if (static::_compile($srcFile, $distFile) === false) {
                return false;
            }
        }
        return str_replace(
            Config::getServerRootPath(),
            '',
            $distFile->getPathname()
        );
    }
}
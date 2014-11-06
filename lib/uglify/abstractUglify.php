<?php
namespace Assets\Uglify;

use SplFileInfo;
use Assets\Helper;
use Assets\Config;

abstract class AbstractUglify
{
    use \Assets\Concerns\Path;

    protected static $_compiledDir = 'uglified';

    public static function uglify($file)
    {
        $file = str_replace(self::_getBaseDir(), '', $file);
        $srcFiles = Helper::loadCompiledFiles($file, static::$_type);
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
                mkdir($distFile->getPath(), 0775, true);
            }

            if (static::_uglify($srcFiles, $distFilePath) === false) {
                return false;
            }
        }

        return str_replace(Config::getServerRootPath(), '', $distFilePath);
    }
}
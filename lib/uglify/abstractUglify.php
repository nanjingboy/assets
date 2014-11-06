<?php
namespace Assets\Uglify;

use SplFileInfo;
use Assets\Helper;
use Assets\Config;

abstract class AbstractUglify
{
    use \Assets\Concerns\Path;

    protected static $_compiledDir = 'uglified';

    private static function _loadSrcFiles($file)
    {
        $compiledFiles = Helper::loadCompiledFiles($file, static::$_type);
        if (empty($compiledFiles)) {
            return array();
        }

        $serverRootPath = Config::getServerRootPath();
        $compiledBaseDir = $serverRootPath . DIRECTORY_SEPARATOR . 'tmp' .
            DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;

        $result = array('files' => array(), 'hashes' => array());
        foreach ($compiledFiles as $compiledFile) {
            $compiledFile = "{$serverRootPath}{$compiledFile}";
            $uglifiedFile = str_replace(
                array(self::_getBaseDir() ,$compiledBaseDir), '', $compiledFile
            );
            $uglifiedFile = $compiledBaseDir . $uglifiedFile . '.min.' .
                md5(filemtime($compiledFile)) . '.' . static::$_type;
            if (file_exists($uglifiedFile) === false) {
                if (static::_uglify(array($compiledFile), $uglifiedFile)) {
                    array_push($result['files'], $uglifiedFile);
                    array_push($result['hashes'], md5(filemtime($uglifiedFile)));
                    continue;
                }
            }

            array_push($result['files'], $compiledFile);
            array_push($result['hashes'], md5(filemtime($compiledFile)));
        }

        return $result;
    }

    public static function uglify($file)
    {
        $file = str_replace(self::_getBaseDir(), '', $file);
        $srcFiles = self::_loadSrcFiles($file);
        if (empty($srcFiles['files'])) {
            return false;
        }

        $distFilePath = self::_getDistFilePath($file, implode('', $srcFiles['hashes']));
        if (file_exists($distFilePath) === false) {
            $distFile = new SplFileInfo($distFilePath);
            if (file_exists($distFile->getPath()) === false) {
                mkdir($distFile->getPath(), 0775, true);
            }

            if (static::_uglify($srcFiles['files'], $distFilePath) === false) {
                return false;
            }
        }

        return str_replace(Config::getServerRootPath(), '', $distFilePath);
    }
}
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
        $result = array('files' => array(), 'hashes' => array());
        foreach ($compiledFiles as $compiledFile) {
            $compiledFile = "{$serverRootPath}{$compiledFile}";
            $minFile = static::parseMinFilePath($compiledFile);
            if (file_exists($minFile) === false) {
                if (static::_uglify(array($compiledFile), $minFile)) {
                    array_push($result['files'], $minFile);
                    array_push($result['hashes'], md5(filemtime($minFile)));
                    continue;
                }
            }

            array_push($result['files'], $compiledFile);
            array_push($result['hashes'], md5(filemtime($compiledFile)));
        }

        return $result;
    }

    public static function parseMinFilePath($srcFile)
    {
        $serverRootPath = Config::getServerRootPath();
        $compiledBaseDir = $serverRootPath . DIRECTORY_SEPARATOR . 'tmp' .
            DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $srcFile = $serverRootPath . str_replace($serverRootPath, '', $srcFile);
        $minFile = str_replace(array(self::_getBaseDir(), $compiledBaseDir), '', $srcFile);
        return $compiledBaseDir . $minFile . '.min.' . md5(filemtime($srcFile)) . '.' . static::$_type;

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
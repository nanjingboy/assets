<?php
namespace Assets\Uglify;

use SplFileInfo;
use AssetLoader;
use Assets\Config;

abstract class AbstractUglify
{
    use \Assets\Concerns\Path;

    protected static $_compiledDirName = 'uglified';

    private static function _loadSrcFiles($file)
    {
        if (static::$_type === 'js') {
            $files = AssetLoader::loadJs($file);
        } else {
            $files = AssetLoader::loadCss($file);
        }

        $result = array('files' => array(), 'hashes' => array());
        $serverRootPath = Config::getServerRootPath();
        foreach ($files as $file) {
            $fileInfo = new SplFileInfo("{$serverRootPath}{$file}");
            $compiler = ucfirst($fileInfo->getExtension());
            if (in_array($compiler, static::$_compilers)) {
                $compiler = "\\Assets\\Compiler\\{$compiler}";
                $compiledFilePath = $compiler::compile(
                    $fileInfo->getPathName()
                );
                $filePath = $serverRootPath . $compiledFilePath;
            } else {
                $filePath = $serverRootPath . $file;
            }
            array_push($result['files'], $filePath);
            array_push($result['hashes'], md5(filemtime($filePath)));
        }

        return $result;

    }

    public static function uglify($file)
    {
        $file = str_replace(self::_getDirectory(), '', $file);
        $srcFiles = self::_loadSrcFiles($file);
        if (empty($srcFiles['files'])) {
            return false;
        }

        $distFilePath = self::_getDistFilePath(
            $file, implode('', $srcFiles['hashes'])
        );

        if (file_exists($distFilePath) === false) {
            $distFile = new SplFileInfo($distFilePath);
            if (file_exists($distFile->getPath()) === false) {
                mkdir($distFile->getPath(), 0774, true);
            }

            if (static::_uglify($srcFiles['files'], $distFilePath) === false) {
                return false;
            }
        }

        return str_replace(Config::getServerRootPath(), '', $distFilePath);
    }
}
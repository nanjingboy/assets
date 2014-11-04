<?php
namespace Assets\Compiler;

use Assets\Helper;

class Css extends AbstractCompiler
{
    protected static $_type = 'css';

    protected static function _urlCompile($file, $srcFileDirPath)
    {
        $contents = file_get_contents($file);
        $matches = array();
        preg_match_all(
            '/(image-url|font-url|url)\s*\(\s*(\'|\")?\s*([^"\'\)]*)\s*(\'|\")?\s*\)\s*/mi',
            $contents,
            $matches
        );
        if (empty($matches[0])) {
            return true;
        }

        $replace = array();
        foreach ($matches[0] as $index => $match) {
            if (strpos($match, 'image-url') === 0) {
                $url = Helper::imageUrl($matches[3][$index]);
            } else if (strpos($match, 'font-url') === 0) {
                $url = Helper::fontUrl($matches[3][$index]);
            } else {
                if (strpos($matches[3][$index], './') === 0) {
                    $url = ltrim($matches[3][$index], './');
                } else {
                    $url = ltrim($matches[3][$index], DIRECTORY_SEPARATOR);
                }
                $parts = array();
                preg_match_all('/^(\.{2}\/)*/', $url, $parts);
                if (!empty($parts[0][0])) {
                    $url = str_replace($parts[0][0], '', $url);
                    $baseDir = realpath($srcFileDirPath . DIRECTORY_SEPARATOR . $parts[0][0]);
                } else {
                    $baseDir = $srcFileDirPath;
                }
                $url = Helper::assetUrl($url, $baseDir);
                if ($url === false) {
                    $url = $matches[3][$index];
                }
            }
            array_push($replace, 'url("' . $url . '")');
        }

        $contents = str_replace($matches[0], $replace, $contents);
        if (file_put_contents($file, $contents) === false) {
            unlink($file);
            return false;
        }

        return true;
    }

    protected static function _compile($srcFile, $distFile)
    {
        if (copy($srcFile->getPathname(), $distFile->getPathname())) {
            return self::_urlCompile($distFile->getPathname(), $srcFile->getPath());
        }

        return false;
    }
}
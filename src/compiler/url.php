<?php
namespace Assets\Compiler;

use Assets\Helper;

trait Url
{
    protected static function _urlCompile($file)
    {
        $contents = file_get_contents($file);
        $matches = array();
        preg_match_all(
            '/(image-url|font-url)\s*\(\s*(\'|\")\s*(.+)\s*(\'|\")\s*\)\s*/mi',
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
            } else {
                $url = Helper::fontUrl($matches[3][$index]);
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
}
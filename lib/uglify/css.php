<?php
namespace Assets\Uglify;

use Assets\Shell;

class Css extends AbstractUglify
{
    protected static $_type = 'css';

    protected static function _uglify($srcFiles, $distFilePath)
    {
        return Shell::run('uglifycss ' . implode(' ', $srcFiles) . ' > ' . $distFilePath);
    }
}
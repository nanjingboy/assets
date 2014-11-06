<?php
namespace Assets\Uglify;

use Assets\Shell;

class Js extends AbstractUglify
{
    protected static $_type = 'js';

    protected static function _uglify($srcFiles, $distFilePath)
    {
        return Shell::run('uglifyjs ' . implode(' ', $srcFiles) . ' -o ' . $distFilePath . ' -m');
    }
}
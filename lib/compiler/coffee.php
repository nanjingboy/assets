<?php
namespace Assets\Compiler;

use Assets\Shell;

class Coffee extends AbstractCompiler
{
    protected static $_type = 'js';

    protected static function _compile($srcFile, $distFile)
    {
        if (copy($srcFile->getPathname(), $distFile->getPathname())) {
            return Shell::run('coffee --compile --no-header --bare ' . $distFile->getPathname());
        }

        return false;
    }
}
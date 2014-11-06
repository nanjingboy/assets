<?php
namespace Assets\Compiler;

use Assets\Shell;

class Less extends Css
{
    protected static function _compile($srcFile, $distFile)
    {
        $command = 'lessc ' . $srcFile->getPathname() . ' > ' . $distFile->getPathname();
        if (Shell::run($command) === true) {
            return self::_urlCompile($distFile->getPathname(), $srcFile->getPath());
        }

        return false;
    }
}
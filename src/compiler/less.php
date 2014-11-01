<?php
namespace Assets\Compiler;

use Assets\Shell;

class Less extends Css
{
    protected static function _compile($srcFile, $distFile)
    {
        $command = 'lessc -x ' . $srcFile->getPathName() . ' > ' . $distFile->getPathName();
        if (Shell::run($command) === true) {
            return self::_urlCompile($distFile->getPathName(), $srcFile->getPath());
        }

        return false;
    }
}
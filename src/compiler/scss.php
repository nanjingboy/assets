<?php
namespace Assets\Compiler;

use Assets\Shell;

class Scss extends Css
{
    protected static function _compile($srcFile, $distFile)
    {
        $command = 'scss --style compressed --no-cache --sourcemap=none '  .
            $srcFile->getPathName() . ' ' . $distFile->getPathName();
        if (Shell::run($command) === true) {
            return self::_urlCompile($distFile->getPathName(), $srcFile->getPath());
        }

        return false;
    }
}
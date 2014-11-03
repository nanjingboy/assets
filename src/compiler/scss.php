<?php
namespace Assets\Compiler;

use Assets\Shell;

class Scss extends Css
{
    protected static function _compile($srcFile, $distFile)
    {
        $command = 'scss --style compressed --no-cache --sourcemap=none '  .
            $srcFile->getPathname() . ' ' . $distFile->getPathname();
        if (Shell::run($command) === true) {
            return self::_urlCompile($distFile->getPathname(), $srcFile->getPath());
        }

        return false;
    }
}
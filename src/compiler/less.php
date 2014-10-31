<?php
namespace Assets\Compiler;

use Assets\Shell;

class Less extends AbstractCompiler
{
    use Url;

    protected static $_type = 'css';

    protected static function _compile($srcFile, $distFile)
    {
        $command = 'lessc -x ' . $srcFile->getPathName() . ' > ' . $distFile->getPathName();
        if (Shell::run($command) === true) {
            return self::_urlCompile($distFile->getPathName());
        }

        return false;
    }
}
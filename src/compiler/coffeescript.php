<?php
namespace Assets\Compiler;

use Assets\Config;
use Assets\Shell;

class Coffeescript extends AbstractCompiler
{
    protected static $_type = 'js';

    protected function _compile($srcFile, $distFile)
    {
        /**
         * As CoffeeScript cli can't set a different file path with compile command,
         * so we have to compile the file in src directory path, and then move to the dist path
         */
        $command = 'coffee --compile --no-header ' . $srcFile->getPathName();
        if (Shell::run($command) === true) {
            return rename(
                rtrim($srcFile->getPathName(), $srcFile->getExtension()) . 'js',
                $distFile->getPathName()
            );
        }

        return false;
    }
}
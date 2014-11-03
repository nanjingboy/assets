<?php
namespace Assets\Compiler;

use Assets\Shell;

class Coffee extends AbstractCompiler
{
    protected static $_type = 'js';

    protected static function _compile($srcFile, $distFile)
    {
        /**
         * As CoffeeScript cli can't set a different file path with compile command,
         * so we have to compile the file in src directory path, and then move to the dist path
         */
        $command = 'coffee --compile --no-header --bare ' . $srcFile->getPathName();
        if (Shell::run($command) === true) {
            return rename(
                rtrim($srcFile->getPathName(), $srcFile->getExtension()) . 'js',
                $distFile->getPathName()
            );
        }

        return false;
    }
}
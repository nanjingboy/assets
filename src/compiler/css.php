<?php
namespace Assets\Compiler;

class Css extends AbstractCompiler
{
    use Url;

    protected static $_type = 'css';

    protected static function _compile($srcFile, $distFile)
    {
        if (copy($srcFile->getPathName(), $distFile->getPathName())) {
            return self::_urlCompile($distFile->getPathName());
        }

        return false;
    }
}
<?php
namespace Test\Compiler;

use PHPUnit_Framework_TestCase;
use Assets\Config;
use Assets\Compiler\Less;

class LessTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFilePath = Less::compile('users/base.less');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/stylesheets/users/base.css',
            Config::getServerRootPath() . $compiledFilePath
        );
    }
}

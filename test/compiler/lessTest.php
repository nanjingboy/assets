<?php
use Assets\Config;
use Assets\Compiler\Less;

class LessTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFilePath = Less::compile('base.less');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/stylesheets/base.css',
            Config::getServerRootPath() . $compiledFilePath
        );
    }
}

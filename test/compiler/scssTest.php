<?php
use Assets\Config;
use Assets\Compiler\Scss;

class ScssTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFilePath = Scss::compile('home.scss');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/stylesheets/home.css',
            Config::getServerRootPath() . $compiledFilePath
        );
    }
}

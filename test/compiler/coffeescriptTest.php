<?php
use Assets\Config;
use Assets\Compiler\Coffeescript;

class CoffeescriptTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFilePath = Coffeescript::compile('users/people.coffee');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/javascripts/users/people.js',
            Config::getServerRootPath() . $compiledFilePath
        );
    }
}

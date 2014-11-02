<?php
use Assets\Config;
use Assets\Compiler\Coffee;

class CoffeeTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFilePath = Coffee::compile('users/people.coffee');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/javascripts/users/people.js',
            Config::getServerRootPath() . $compiledFilePath
        );
    }
}

<?php
use Assets\Config;
use Assets\Uglify\Js;

class JsTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $uglifiedFilePath = Js::Uglify('base.js');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/javascripts/base_uglified.js',
            Config::getServerRootPath() . $uglifiedFilePath
        );
    }
}

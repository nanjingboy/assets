<?php
namespace Test\Uglify;

use PHPUnit_Framework_TestCase;
use Assets\Config;
use Assets\Uglify\Css;

class CssTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $uglifiedFilePath = Css::Uglify('home');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/stylesheets/home_uglified.css',
            Config::getServerRootPath() . $uglifiedFilePath
        );
    }
}

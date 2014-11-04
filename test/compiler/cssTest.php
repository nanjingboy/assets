<?php
namespace Test\Compiler;

use PHPUnit_Framework_TestCase;
use Assets\Config;
use Assets\Compiler\Css;

class CssTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFilePath = Css::compile('url_replace.css');
        $this->assertFileEquals(
            Config::getServerRootPath() . '/expected/assets/stylesheets/url_replace.css',
            Config::getServerRootPath() . $compiledFilePath
        );
    }
}

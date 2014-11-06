<?php
use Assets\Config;
use Assets\Helper;

class HelperTest extends PHPUnit_Framework_TestCase
{
    public function testLoadCompiledFiles()
    {
        $this->assertEquals(
            array(
                '/tmp/assets/users_people_6baefd0e20cdae331f3debebc5cd19e7.js',
                '/assets/javascripts/base.js'
            ),
            Helper::loadCompiledFiles('base', 'js')
        );

        $this->assertEquals(
            array(
                '/tmp/assets/url_replace_6baefd0e20cdae331f3debebc5cd19e7.css',
                '/tmp/assets/users_base_6baefd0e20cdae331f3debebc5cd19e7.css',
                '/tmp/assets/home_eedb46782cc1f9b1da2ee8145fdecebf.css'
            ),
            Helper::loadCompiledFiles('home', 'css')
        );
    }

    public function testAssetUrl()
    {
        $baseDir = Config::getImagesPath();
        $this->assertEquals(
            'http://somehost.com/image.png',
            Helper::assetUrl('http://somehost.com/image.png', $baseDir)
        );
        $this->assertEquals(
            'https://somehost.com/image.png',
            Helper::assetUrl('https://somehost.com/image.png', $baseDir)
        );
        $this->assertEquals(
            "data:image/png:base64,0",
            Helper::assetUrl('data:image/png:base64,0', $baseDir)
        );
        $this->assertEquals(
            '/assets/images/banner.png',
            Helper::assetUrl('banner.png', $baseDir)
        );
    }

    public function testRemovePath()
    {
        $dir = Config::getServerRootPath() . DIRECTORY_SEPARATOR . 'remove' .
            DIRECTORY_SEPARATOR . 'directory' . DIRECTORY_SEPARATOR . 'recursive';
        mkdir($dir, 0777, true);
        $this->assertTrue(Helper::removePath($dir));
        $this->assertFalse(file_exists($dir));
    }
}

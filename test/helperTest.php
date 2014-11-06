<?php
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

    public function testImageUrl()
    {
        $this->assertEquals(
            'http://somehost.com/image.png',
            Helper::imageUrl('http://somehost.com/image.png')
        );
        $this->assertEquals(
            'https://somehost.com/image.png',
            Helper::imageUrl('https://somehost.com/image.png')
        );
        $this->assertEquals(
            "data:image/png:base64,0",
            Helper::imageUrl('data:image/png:base64,0')
        );
        $this->assertEquals(
            '/assets/images/banner.png',
            Helper::imageUrl('banner.png')
        );
    }

    public function testFontUrl()
    {
        $this->assertEquals(
            'http://somehost.com/font.eot',
            Helper::imageUrl('http://somehost.com/font.eot')
        );
        $this->assertEquals(
            'https://somehost.com/font.eot',
            Helper::imageUrl('https://somehost.com/font.eot')
        );
        $this->assertEquals(
            "data:application/font-eot:base64,0",
            Helper::imageUrl('data:application/font-eot:base64,0')
        );
        $this->assertEquals(
            '/assets/fonts/font.eot',
            Helper::fontUrl('font.eot')
        );
    }
}

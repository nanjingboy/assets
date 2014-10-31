<?php
use Assets\Helper;

class HelperTest extends PHPUnit_Framework_TestCase
{
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

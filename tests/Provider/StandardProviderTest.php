<?php

namespace Provider;

use Bangpound\oEmbed\Provider\StandardProvider;

class StandardProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupport()
    {
        $provider = new StandardProvider('', array(
          'http://*.example.com/*',
          'https://example.com/video/*',
        ));

        $this->assertFalse($provider->supports('http://example.com/video'));
        $this->assertTrue($provider->supports('http://video.example.com/something'));
        $this->assertFalse($provider->supports('http://example.com/video/something'));
        $this->assertTrue($provider->supports('https://example.com/video/something'));
    }
}

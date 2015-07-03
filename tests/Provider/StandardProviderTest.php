<?php

namespace Bangpound\oEmbed\Test\Provider;

use Bangpound\oEmbed\Provider\StandardProvider;

class StandardProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSupport
     */
    public function testSupport($scheme, $url, array $params = array(), $expected)
    {
        $provider = new StandardProvider('', $scheme);
        $this->assertEquals($expected, $provider->supports($url));
    }

    /**
     * @dataProvider provideRequest
     */
    public function testRequest($url, array $params = array())
    {
        $provider = new StandardProvider('');
        $request = $provider->request($url, $params);
        $this->assertInstanceOf('Psr\\Http\\Message\\RequestInterface', $request);
    }

    public function provideSupport()
    {
        return array(
          [array('http://*.example.com/*', 'https://example.com/video/*'), 'http://example.com/video', array(), false],
          [array('http://*.example.com/*', 'https://example.com/video/*'), 'http://video.example.com/something', array(), true],
          [array('http://*.example.com/*', 'https://example.com/video/*'), 'http://example.com/video/something', array(), false],
          [array('http://*.example.com/*', 'https://example.com/video/*'), 'https://example.com/video/something', array(), true],
          [array(), 'https://example.com/video/something', array(), false],
        );
    }

    public function provideRequest()
    {
        return array(
          ['http://video.example.com/something'],
          ['https://example.com/video/something'],
        );
    }
}

<?php

namespace Bangpound\oEmbed\Test\Provider;

use Bangpound\oEmbed\Provider\DelegatingProvider;
use Bangpound\oEmbed\Provider\ProviderResolver;
use Bangpound\oEmbed\Provider\StandardProvider;

class DelegatingProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSupport
     *
     * @param $scheme
     * @param $url
     * @param array $params
     * @param $expected
     */
    public function testSupport($scheme, $url, array $params = array(), $expected)
    {
        $resolver = new ProviderResolver([
            new StandardProvider('', $scheme),
        ]);
        $provider = new DelegatingProvider($resolver);
        $this->assertEquals($expected, $provider->supports($url, $params));
    }

    /**
     * @dataProvider provideRequest
     *
     * @param $scheme
     * @param $url
     * @param array $params
     */
    public function testRequest($scheme, $url, array $params = array())
    {
        $resolver = new ProviderResolver([
          new StandardProvider('', $scheme),
        ]);
        $provider = new DelegatingProvider($resolver);
        $request = $provider->request($url, $params);
        $this->assertInstanceOf('Psr\\Http\\Message\\RequestInterface', $request);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRequestException()
    {
        $resolver = new ProviderResolver([
          new StandardProvider(''),
        ]);
        $provider = new DelegatingProvider($resolver);
        $provider->request('', array());
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
          [array('http://video.example.com/*'), 'http://video.example.com/something'],
          [array('https://example.com/video/*'), 'https://example.com/video/something'],
        );
    }
}

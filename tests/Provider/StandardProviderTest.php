<?php

namespace Bangpound\oEmbed\Test\Provider;

use Bangpound\oEmbed\Provider\StandardProvider;
use Bangpound\oEmbed\Provider\ProviderInterface;

class StandardProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    public function setUp()
    {
        $this->provider = new StandardProvider('http://example.com/oembed', array(
          'http://*.example.com/*',
          'https://example.com/video/*',
        ));
    }

    public function testSupport()
    {
        $this->assertFalse($this->provider->supports('http://example.com/video'));
        $this->assertTrue($this->provider->supports('http://video.example.com/something'));
        $this->assertFalse($this->provider->supports('http://example.com/video/something'));
        $this->assertTrue($this->provider->supports('https://example.com/video/something'));
    }

    public function testRequest()
    {
        $request = $this->provider->request('http://video.example.com/something');
        $this->assertInstanceOf('Psr\\Http\\Message\\RequestInterface', $request);

        $request = $this->provider->request('https://example.com/video/something');
        $this->assertInstanceOf('Psr\\Http\\Message\\RequestInterface', $request);
    }
}

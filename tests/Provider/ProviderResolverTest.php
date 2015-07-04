<?php

namespace Bangpound\oEmbed\Test\Provider;

use Bangpound\oEmbed\Provider\ProviderResolver;
use Bangpound\oEmbed\Provider\StandardProvider;

class ProviderResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $resolver = new ProviderResolver([
          new StandardProvider('', array(
            'http://example.com/video/*',
          )),
          new StandardProvider(''),
        ]);

        $url = 'http://example.com/video/1';
        $this->assertInstanceOf('Bangpound\\oEmbed\\Provider\\ProviderInterface', $resolver->resolve($url));

        $url = 'http://deadend.com';
        $this->assertFalse($resolver->resolve($url));

        foreach ($resolver->getProviders() as $provider) {
            $this->assertInstanceOf('Bangpound\\oEmbed\\Provider\\ProviderInterface', $provider);
        }
    }
}

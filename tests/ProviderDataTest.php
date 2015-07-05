<?php

namespace Bangpound\oEmbed\Test;

use Bangpound\oEmbed\Negotiation\FormatNegotiator;
use Bangpound\oEmbed\Provider\DiscoverProvider;
use Bangpound\oEmbed\Provider\ProviderResolver;
use Bangpound\oEmbed\Provider\StandardProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;

class ProviderDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider schemeProviders
     *
     * @param $url
     * @param array $params
     */
    public function testSchemeProviders($url, array $params = array())
    {
        $providerInfo = self::loadProviderData();

        $resolver = new ProviderResolver();
        foreach ($providerInfo as $infos) {
            foreach ($infos as $info) {
                if (isset($info['endpoint']) && isset($info['scheme'])) {
                    $provider = new StandardProvider($info['endpoint'], $info['scheme']);
                    $resolver->addProvider($provider);
                }
            }
        }

        $provider = $resolver->resolve($url, $params);
        $this->assertInstanceOf('Bangpound\\oEmbed\\Provider\\ProviderInterface', $provider);

        $request = $provider->request($url, $params);
        $this->assertInstanceOf('Psr\\Http\\Message\\RequestInterface', $request);
    }

    /**
     * @dataProvider discoveryProviders
     *
     * @param $url
     * @param array $params
     * @param array $queue
     */
    public function testDiscoveryProviders($url, array $params = array(), array $queue = array())
    {
        $resolver = new ProviderResolver();

        // Create a mock handler and queue response.
        $mock = new MockHandler($queue);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $negotiator = new \Bangpound\oEmbed\Negotiation\FormatNegotiator();
        $provider = new DiscoverProvider($client, $negotiator);
        $resolver->addProvider($provider);

        $provider = $resolver->resolve($url, $params);
        $this->assertInstanceOf('Bangpound\\oEmbed\\Provider\\ProviderInterface', $provider);

        $request = $provider->request($url, $params);
        $this->assertInstanceOf('Psr\\Http\\Message\\RequestInterface', $request);
    }

    public function schemeProviders()
    {
        $providerInfo = self::loadProviderData();

        $data = array();
        foreach ($providerInfo as $providers) {
            foreach ($providers as $info) {
                if (isset($info['test']) && isset($info['endpoint']) && isset($info['scheme'])) {
                    foreach ($info['test'] as $url) {
                        $data[] = array($url);
                    }
                }
            }
        }

        return $data;
    }

    public function discoveryProviders()
    {
        $providerInfo = self::loadProviderData();

        $data = array();
        foreach ($providerInfo as $providers) {
            foreach ($providers as $info) {
                if (isset($info['test']) && isset($info['discovery']) && $info['discovery']) {
                    $makeRequest = function ($url) use ($info) {
                        return new Psr7\Response(200, [], '<html><head><link rel="alternate" type="application/json+oembed" href="'.$info['endpoint'].'?url='.$url.'"></head><body></body></html>');
                    };

                    foreach ($info['test'] as $url) {
                        $data[] = array($url, [], [$makeRequest($url), $makeRequest($url)]);
                    }
                }
            }
        }

        return $data;
    }

    private static function loadProviderData()
    {
        $json = file_get_contents(__DIR__.'/../data/providers.json');
        $json = json_decode($json, true);

        return array_map(function ($value) {
            return $value['providers'];
        }, $json);
    }
}

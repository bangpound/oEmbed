<?php

namespace Bangpound\oEmbed\Provider;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class DiscoverProvider implements ProviderInterface
{
    const LINK_XPATH = '//head/link[@rel = \'alternate\' and substring(@type, string-length(@type) - string-length(\'+oembed\') + 1) = \'+oembed\']';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $client = clone $client;
        /** @var \GuzzleHttp\HandlerStack $handler */
        $handler = $client->getConfig('handler');
        $handler->push(function(callable $fn) {

            /*
             * @param \Psr\Http\Message\RequestInterface $request
             * @param array $options
             * @return \GuzzleHttp\RedirectMiddleware
             */
            return function(RequestInterface $request, array $options) use ($fn) {
                return $fn($request, $options)
                    ->then(function(ResponseInterface $response) use ($fn, $request, $options) {
                        $contents = $response->getBody()->getContents();
                        $crawler = new Crawler($contents);
                        $parts = $crawler->filterXPath(self::LINK_XPATH)->extract('href');
                        if (!empty($parts)) {
                            $request = Psr7\modify_request($request, array(
                                'uri' => new Psr7\Uri($parts[0]),
                            ));
                            $response = $fn($request, $options);
                        }

                        return $response;
                    });
            };
        }, 'oembed_discover');
        $this->client = $client;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, $params = array())
    {
        $request = new Psr7\Request('get', $url);
        $response = $this->client->send($request);

        return $response->getStatusCode() === 200;
    }

    private static function headerOembedLinks(ResponseInterface $response)
    {
        $links = $response->getHeader('link');
        $links = Psr7\parse_header($links);

        return array_map(array(__CLASS__, 'parseUrl'),
            array_filter($links, function($link) {
                return ($link['rel'] === 'alternate'
                && isset($link['type'])
                && strpos($link['type'], '+oembed')
                );
            }));
    }

    private static function headerCanonicalUrls(ResponseInterface $response)
    {
        $links = $response->getHeader('link');
        $links = Psr7\parse_header($links);

        return array_map(array(__CLASS__, 'parseUrl'),
            array_filter($links, function($link) {
                return ($link['rel'] === 'shortlink');
            }));
    }

    private static function parseUrl($link)
    {
        return preg_replace('/^<(.+?)>$/', '\1', $link[0]);
    }

    /**
     * @param $url
     * @param array $params
     *
     * @return Psr7\Request
     */
    public function request($url, $params = array())
    {
        $request = new Psr7\Request('get', $url);

        return $request;
    }
}

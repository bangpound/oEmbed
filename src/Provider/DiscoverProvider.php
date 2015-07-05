<?php

namespace Bangpound\oEmbed\Provider;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class DiscoverProvider implements ProviderInterface
{
    const LINK_ANY_XPATH = '//head/link[@rel = \'alternate\' and (@type = \'application/json+oembed\' or @type = \'text/xml+oembed\')]';
    const LINK_JSON_XPATH = '//head/link[@rel = \'alternate\' and @type = \'application/json+oembed\']';
    const LINK_XML_XPATH = '//head/link[@rel = \'alternate\' and @type = \'text/xml+oembed\']';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private $map = array(
      'application/json+oembed' => 'json',
      'text/xml+oembed' => 'xml',
    );

    /**
     * @param \GuzzleHttp\ClientInterface $client
     * @param array                       $map
     */
    public function __construct(ClientInterface $client, array $map = null)
    {
        $this->client = $client;
        if (isset($map)) {
            $this->map = $map;
        }
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, array $params = array())
    {
        $links = $this->discoverLinks($url, $params);

        return !empty($links);
    }

    /**
     * @param $url
     * @param array $params
     *
     * @return Psr7\Request
     */
    public function request($url, array $params = array())
    {
        $links = self::discoverLinks($url, $params);

        $uri = new Psr7\Uri($links[0][0]);

        return new Psr7\Request('get', $uri);
    }

    private function discoverLinks($url, array $params = array())
    {
        $request = new Psr7\Request('get', $url);
        $response = $this->client->send($request);

        $params = array_merge(array('format' => null), $params);
        switch ($params['format']) {
            case 'json':
                $xpath = self::LINK_JSON_XPATH;
                break;
            case 'xml':
                $xpath = self::LINK_XML_XPATH;
                break;
            default:
                $xpath = self::LINK_ANY_XPATH;
                break;
        }
        $links = self::responseBodyOEmbedLinks($response, $xpath);

        if (!empty($links) && isset($params['format'])) {
            $links = array_filter($links, function ($link) use ($params) {
                return isset($this->map[$link[1]]) && $params['format'] === $this->map[$link[1]];
            });
        }

        return $links;
    }

    private static function responseBodyOEmbedLinks(ResponseInterface $response, $xpath)
    {
        $contents = $response->getBody()->getContents();
        $crawler = new Crawler($contents);

        return $crawler->filterXPath($xpath)->extract(array('href', 'type'));
    }
}

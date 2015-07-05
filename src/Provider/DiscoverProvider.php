<?php

namespace Bangpound\oEmbed\Provider;

use Bangpound\oEmbed\Exception\UnknownFormatException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;
use Negotiation\FormatNegotiatorInterface;
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
     * @var FormatNegotiatorInterface
     */
    private $negotiator;

    /**
     * @param ClientInterface           $client
     * @param FormatNegotiatorInterface $negotiator
     */
    public function __construct(ClientInterface $client, FormatNegotiatorInterface $negotiator)
    {
        $this->client = $client;
        $this->negotiator = $negotiator;
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

    /**
     * @param string $url
     * @param array  $params
     *
     * @return array
     */
    private function discoverLinks($url, array $params = array())
    {
        $request = new Psr7\Request('get', $url);
        $response = $this->client->send($request);

        $xpath = isset($params['format']) ? self::xpathForFormat($params['format']) : self::LINK_ANY_XPATH;
        $links = self::responseBodyOEmbedLinks($response, $xpath);

        if (!empty($links) && isset($params['format'])) {
            $links = array_filter($links, function ($link) use ($params) {
                return $this->negotiator->getFormat($link[1]) !== null && $params['format'] === $this->negotiator->getFormat($link[1]);
            });
        }

        return $links;
    }

    /**
     * @param $format
     *
     * @return string
     */
    private static function xpathForFormat($format)
    {
        if ($format === 'json') {
            return self::LINK_JSON_XPATH;
        }

        if ($format === 'xml') {
            return self::LINK_XML_XPATH;
        }

        throw new UnknownFormatException();
    }

    /**
     * @param ResponseInterface $response
     * @param string            $xpath
     *
     * @return array
     */
    private static function responseBodyOEmbedLinks(ResponseInterface $response, $xpath)
    {
        $contents = $response->getBody()->getContents();
        $crawler = new Crawler($contents);

        return $crawler->filterXPath($xpath)->extract(array('href', 'type'));
    }
}

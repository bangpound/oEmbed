<?php

namespace Bangpound\oEmbed;

use Bangpound\oEmbed\Provider\ProviderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Bangpound\oEmbed\Serializer\Serializer;

/**
 * Class Consumer.
 */
class Consumer
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param ClientInterface $client
     * @param Serializer      $serializer
     */
    public function __construct(ClientInterface $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @param ProviderInterface $provider
     * @param $url
     * @param array $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function get(ProviderInterface $provider, $url, $params = array())
    {
        $uri = self::makeUri($provider, $url, $params);
        $request = new Psr7\Request('get', $uri);
        $response = $this->client->send($request);

        $data = $response->getBody()->getContents();
        $format = $this->getFormat($params, $response);

        return $this->serializer->deserialize($data, null, $format);
    }

    private static function makeUri(ProviderInterface $provider, $url, $params = array())
    {
        $uri = \GuzzleHttp\uri_template($provider->getEndpoint(), $params);
        $uri = new Psr7\Uri($uri);

        // All arguments must be urlencoded (as per RFC 1738).
        $query = Psr7\build_query($params, PHP_QUERY_RFC1738);
        $uri = $uri->withQuery($query);

        return Psr7\Uri::withQueryValue($uri, 'url', $url);
    }

    private function getFormat(array $params, ResponseInterface $response)
    {
        if ($response->hasHeader('content-type')) {
            return $this->getFormatFromContentType($response);
        }
        if (isset($params['format'])) {
            return $params['format'];
        } else {
            throw new \Exception('Unable to figure out the content type');
        }
    }

    private function getFormatFromContentType(ResponseInterface $response)
    {
        $contentType = Psr7\parse_header($response->getHeader('content-type'))[0][0];
        switch ($contentType) {
            case 'application/xml':
            case 'text/xml':
                return 'xml';
            case 'application/json':
                return 'json';
        }
    }
}

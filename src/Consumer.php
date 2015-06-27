<?php

namespace Bangpound\oEmbed;

use Bangpound\oEmbed\Provider\ProviderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;

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
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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

        if ($response->hasHeader('content-type')) {
            $format = Psr7\parse_header($response->getHeader('content-type'))[0][0];
        } elseif (isset($params['format'])) {
            $format = $params['format'];
        } else {
            throw new \Exception();
        }

        $contents = $response->getBody()->getContents();
        switch ($format) {
            case 'text/xml':
                return (array) new \SimpleXMLElement($contents);
            case 'application/json':
                return json_decode($contents, true);
            default:
                throw new \Exception();
        }
    }

    private static function makeUri(ProviderInterface $provider, $url, $params = array())
    {
        $uri = new Psr7\Uri($provider->getEndpoint());

        // All arguments must be urlencoded (as per RFC 1738).
        $query = Psr7\build_query($params, PHP_QUERY_RFC1738);
        $uri = $uri->withQuery($query);

        return Psr7\Uri::withQueryValue($uri, 'url', $url);
    }
}

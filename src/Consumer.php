<?php

namespace Bangpound\oEmbed;

use Bangpound\oEmbed\Exception\UnknownFormatException;
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
        $request = $provider->request($url, $params);
        $response = $this->client->send($request);

        $data = $response->getBody()->getContents();
        $format = self::getFormat($params, $response);

        return $this->serializer->deserialize($data, null, $format);
    }

    private static function getFormat(array $params, ResponseInterface $response)
    {
        if ($response->hasHeader('content-type')) {
            return self::getFormatFromContentType($response);
        }
        if (isset($params['format'])) {
            return $params['format'];
        } else {
            throw new UnknownFormatException('Unable to figure out the format');
        }
    }

    private static function getFormatFromContentType(ResponseInterface $response)
    {
        $contentType = Psr7\parse_header($response->getHeader('content-type'))[0][0];
        switch ($contentType) {
            case 'application/xml':
            case 'text/xml':
                return 'xml';
            case 'application/json':
                return 'json';
            default:
                throw new UnknownFormatException('Content type header does not map to a supported format');
        }
    }
}

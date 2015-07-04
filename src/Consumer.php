<?php

namespace Bangpound\oEmbed;

use Bangpound\oEmbed\Exception\UnknownFormatException;
use Bangpound\oEmbed\Provider\ProviderInterface;
use GuzzleHttp\ClientInterface;
use Negotiation\FormatNegotiatorInterface;
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
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var FormatNegotiatorInterface
     */
    private $negotiator;

    /**
     * @param ClientInterface   $client
     * @param ProviderInterface $provider
     * @param Serializer        $serializer
     */
    public function __construct(ClientInterface $client, ProviderInterface $provider, Serializer $serializer, FormatNegotiatorInterface $negotiator)
    {
        $this->client = $client;
        $this->provider = $provider;
        $this->serializer = $serializer;
        $this->negotiator = $negotiator;
    }

    /**
     * @param $url
     * @param array $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function get($url, $params = array())
    {
        $request = $this->provider->request($url, $params);
        $response = $this->client->send($request);

        $data = $response->getBody()->getContents();
        $format = $this->getFormat($params, $response);

        return $this->serializer->deserialize($data, null, $format);
    }

    private function getFormat(array $params, ResponseInterface $response)
    {
        if ($response->hasHeader('content-type')) {
            $header = $response->getHeaderLine('content-type');

            return $this->negotiator->getFormat($header);
        }
        if (isset($params['format'])) {
            return $params['format'];
        }
        throw new UnknownFormatException('Unable to figure out the format');
    }
}

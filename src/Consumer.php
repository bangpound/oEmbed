<?php

namespace Bangpound\oEmbed;

use Bangpound\oEmbed\Exception\UnknownFormatException;
use Bangpound\oEmbed\Provider\ProviderInterface;
use GuzzleHttp\ClientInterface;
use Negotiation\FormatNegotiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FormatNegotiatorInterface
     */
    private $negotiator;

    /**
     * @param ClientInterface           $client
     * @param ProviderInterface         $provider
     * @param SerializerInterface       $serializer
     * @param FormatNegotiatorInterface $negotiator
     */
    public function __construct(ClientInterface $client, ProviderInterface $provider, SerializerInterface $serializer, FormatNegotiatorInterface $negotiator)
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
     */
    public function get($url, $params = array())
    {
        $request = $this->provider->request($url, $params);
        $response = $this->client->send($request);

        $data = $response->getBody()->getContents();
        $format = $this->getFormat($params, $response);

        return $this->serializer->deserialize($data, null, $format);
    }

    /**
     * @param array             $params
     * @param ResponseInterface $response
     *
     * @return null|string
     *
     * @throws UnknownFormatException
     */
    private function getFormat(array $params, ResponseInterface $response)
    {
        if ($response->hasHeader('content-type')) {
            $header = $response->getHeaderLine('content-type');
            $format = $this->negotiator->getFormat($header);
        }

        if (isset($params['format'])) {
            $format = $params['format'];
        }

        if (!isset($format)) {
            throw new UnknownFormatException('Unable to figure out the format');
        }

        return $format;
    }
}

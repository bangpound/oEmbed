<?php

namespace Bangpound\oEmbed\Test;

use Bangpound\oEmbed\Consumer;
use Bangpound\oEmbed\Provider\StandardProvider;
use Bangpound\oEmbed\Serializer\Serializer;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Consumer
     */
    private $consumer;

    public function setUp()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
          new Response(200, [
              'Content-Type' => 'application/json',
          ], '{"author_url":"http:\/\/www.youtube.com\/user\/simonyapp","thumbnail_width":480,"author_name":"Simon Yapp","height":344,"title":"TEST VIDEO","width":459,"version":"1.0","provider_name":"YouTube","html":"\u003ciframe width=\"459\" height=\"344\" src=\"https:\/\/www.youtube.com\/embed\/C0DPdy98e4c?feature=oembed\" frameborder=\"0\" allowfullscreen\u003e\u003c\/iframe\u003e","provider_url":"http:\/\/www.youtube.com\/","thumbnail_url":"https:\/\/i.ytimg.com\/vi\/C0DPdy98e4c\/hqdefault.jpg","type":"video","thumbnail_height": 360}'),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $serializer = new \Symfony\Component\Serializer\Serializer([
          new PropertyNormalizer(null, $nameConverter),
          new GetSetMethodNormalizer(null, $nameConverter),
        ], [
          new JsonEncoder(),
        ]);
        $serializer = new Serializer($serializer);

        $this->consumer = new Consumer($client, $serializer);
    }

    public function testGet()
    {
        $provider = new StandardProvider('');
        /** @var \Bangpound\oEmbed\Response\VideoResponse $response */
        $response = $this->consumer->get($provider, 'https://www.youtube.com/watch?v=C0DPdy98e4c');

        $this->assertInstanceOf('Bangpound\\oEmbed\\Response\\Response', $response);
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getThumbnailHeight());
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getThumbnailWidth());

        $this->assertInstanceOf('Bangpound\\oEmbed\\Response\\VideoResponse', $response);
        $this->assertContains('iframe', $response->getHtml());
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getHeight());
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getWidth());
    }
}

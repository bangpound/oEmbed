<?php

namespace Bangpound\oEmbed\Test;

use Bangpound\oEmbed\Consumer;
use Bangpound\oEmbed\Provider\ProviderInterface;
use Bangpound\oEmbed\Provider\StandardProvider;
use Bangpound\oEmbed\Serializer\Serializer;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    public function test__construct()
    {
        $client = new Client();
        $serializer = new \Symfony\Component\Serializer\Serializer();
        $serializer = new Serializer($serializer);
        $consumer = new Consumer($client, $serializer);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testBrokenConstructor()
    {
        new Consumer();
    }

    /**
     * @dataProvider responseProvider
     *
     * @param \Bangpound\oEmbed\Provider\ProviderInterface $provider
     * @param $url
     * @param array                               $params
     * @param \Psr\Http\Message\ResponseInterface $expectedResponse
     */
    public function testGet(
      ProviderInterface $provider,
      $url,
      array $params = array(),
      ResponseInterface $expectedResponse
    ) {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
          $expectedResponse,
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $serializer = new \Symfony\Component\Serializer\Serializer([
          new PropertyNormalizer(null, $nameConverter),
          new GetSetMethodNormalizer(null, $nameConverter),
        ], [
          new JsonEncoder(),
          new XmlEncoder(),
        ]);
        $serializer = new Serializer($serializer);

        $consumer = new Consumer($client, $serializer);
        $response = $consumer->get($provider, $url, $params);

        $this->assertInstanceOf('Bangpound\\oEmbed\\Response\\Response',
          $response);
    }

    /**
     * @expectedException \Bangpound\oEmbed\Exception\UnknownFormatException
     */
    public function testGetUnknownFormatException()
    {
        $this->testGet(new StandardProvider(''), '', array(), new Response());
    }

    /**
     * @expectedException \Bangpound\oEmbed\Exception\UnknownFormatException
     */
    public function testGetUnknownContentTypeException()
    {
        $this->testGet(new StandardProvider(''), '', array(), new Response(200, ['Content-Type' => 'text/plain']));
    }

    /**
     * @return array
     */
    public function responseProvider()
    {
        return array(
          [
            new StandardProvider(''),
            'https://www.youtube.com/watch?v=C0DPdy98e4c',
            array(),
            new Response(200, ['Content-Type' => 'application/json'], '{"author_url":"http:\/\/www.youtube.com\/user\/simonyapp","thumbnail_width":480,"author_name":"Simon Yapp","height":344,"title":"TEST VIDEO","width":459,"version":"1.0","provider_name":"YouTube","html":"\u003ciframe width=\"459\" height=\"344\" src=\"https:\/\/www.youtube.com\/embed\/C0DPdy98e4c?feature=oembed\" frameborder=\"0\" allowfullscreen\u003e\u003c\/iframe\u003e","provider_url":"http:\/\/www.youtube.com\/","thumbnail_url":"https:\/\/i.ytimg.com\/vi\/C0DPdy98e4c\/hqdefault.jpg","type":"video","thumbnail_height": 360}'),
          ],
          [
            new StandardProvider(''),
            'https://www.youtube.com/watch?v=C0DPdy98e4c',
            array(),
            new Response(200, ['Content-Type' => 'text/xml'], '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL.'<oembed><html>&lt;iframe width="459" height="344" src="https://www.youtube.com/embed/C0DPdy98e4c?feature=oembed" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;</html><author_name>Simon Yapp</author_name><width>459</width><provider_name>YouTube</provider_name><thumbnail_width>480</thumbnail_width><thumbnail_url>https://i.ytimg.com/vi/C0DPdy98e4c/hqdefault.jpg</thumbnail_url><thumbnail_height>360</thumbnail_height><version>1.0</version><type>video</type><provider_url>http://www.youtube.com/</provider_url><height>344</height><title>TEST VIDEO</title><author_url>http://www.youtube.com/user/simonyapp</author_url></oembed>'),
          ],
          [
            new StandardProvider(''),
            'https://www.youtube.com/watch?v=C0DPdy98e4c',
            array('format' => 'json'),
            new Response(200, [], '{"author_url":"http:\/\/www.youtube.com\/user\/simonyapp","thumbnail_width":480,"author_name":"Simon Yapp","height":344,"title":"TEST VIDEO","width":459,"version":"1.0","provider_name":"YouTube","html":"\u003ciframe width=\"459\" height=\"344\" src=\"https:\/\/www.youtube.com\/embed\/C0DPdy98e4c?feature=oembed\" frameborder=\"0\" allowfullscreen\u003e\u003c\/iframe\u003e","provider_url":"http:\/\/www.youtube.com\/","thumbnail_url":"https:\/\/i.ytimg.com\/vi\/C0DPdy98e4c\/hqdefault.jpg","type":"video","thumbnail_height": 360}'),
          ],
          [
            new StandardProvider(''),
            'https://www.youtube.com/watch?v=C0DPdy98e4c',
            array('format' => 'xml'),
            new Response(200, [], '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL.'<oembed><html>&lt;iframe width="459" height="344" src="https://www.youtube.com/embed/C0DPdy98e4c?feature=oembed" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;</html><author_name>Simon Yapp</author_name><width>459</width><provider_name>YouTube</provider_name><thumbnail_width>480</thumbnail_width><thumbnail_url>https://i.ytimg.com/vi/C0DPdy98e4c/hqdefault.jpg</thumbnail_url><thumbnail_height>360</thumbnail_height><version>1.0</version><type>video</type><provider_url>http://www.youtube.com/</provider_url><height>344</height><title>TEST VIDEO</title><author_url>http://www.youtube.com/user/simonyapp</author_url></oembed>'),
          ],
        );
    }
}

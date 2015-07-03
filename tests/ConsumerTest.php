<?php

namespace Bangpound\oEmbed;

use Bangpound\oEmbed\Provider\StandardProvider;
use Bangpound\oEmbed\Serializer\Serializer;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $provider = new StandardProvider('http://www.youtube.com/oembed');
        $client = new Client();
        $serializer = new \Symfony\Component\Serializer\Serializer([new PropertyNormalizer(null, new CamelCaseToSnakeCaseNameConverter()), new GetSetMethodNormalizer(null, new CamelCaseToSnakeCaseNameConverter())], [new JsonEncoder()]);
        $serializer = new Serializer($serializer);
        $consumer = new Consumer($client, $serializer);
        /** @var \Bangpound\oEmbed\Response\VideoResponse $response */
        $response = $consumer->get($provider, 'https://www.youtube.com/watch?v=0fOHh5Q7Q1E');

        $this->assertInstanceOf('Bangpound\\oEmbed\\Response\\Response', $response);
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getThumbnailHeight());
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getThumbnailWidth());

        $this->assertInstanceOf('Bangpound\\oEmbed\\Response\\VideoResponse', $response);
        $this->assertContains('iframe', $response->getHtml());
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getHeight());
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT, $response->getWidth());
    }
}

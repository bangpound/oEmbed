<?php

namespace Bangpound\oEmbed\Test\Response;

use Bangpound\oEmbed\Response\Response;

class ResponseTest  extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider responseProvider
     */
    public function test($properties)
    {
        $response = new Response();
        $reflection = new \ReflectionClass($response);

        $property = $reflection->getProperty('title');
        $property->setAccessible(true);
        $property->setValue($response, $properties['title']);
        $this->assertEquals($properties['title'], $response->getTitle());

        $property = $reflection->getProperty('version');
        $property->setAccessible(true);
        $property->setValue($response, $properties['version']);
        $this->assertEquals($properties['version'], $response->getVersion());

        $property = $reflection->getProperty('type');
        $property->setAccessible(true);
        $property->setValue($response, $properties['type']);
        $this->assertEquals($properties['type'], $response->getType());

        $property = $reflection->getProperty('authorName');
        $property->setAccessible(true);
        $property->setValue($response, $properties['authorName']);
        $this->assertEquals($properties['authorName'], $response->getAuthorName());

        $property = $reflection->getProperty('authorUrl');
        $property->setAccessible(true);
        $property->setValue($response, $properties['authorUrl']);
        $this->assertEquals($properties['authorUrl'], $response->getAuthorUrl());

        $property = $reflection->getProperty('providerName');
        $property->setAccessible(true);
        $property->setValue($response, $properties['providerName']);
        $this->assertEquals($properties['providerName'], $response->getProviderName());

        $property = $reflection->getProperty('providerUrl');
        $property->setAccessible(true);
        $property->setValue($response, $properties['providerUrl']);
        $this->assertEquals($properties['providerUrl'], $response->getProviderUrl());

        $property = $reflection->getProperty('cacheAge');
        $property->setAccessible(true);
        $property->setValue($response, $properties['cacheAge']);
        $this->assertEquals($properties['cacheAge'], $response->getCacheAge());

        $property = $reflection->getProperty('thumbnailUrl');
        $property->setAccessible(true);
        $property->setValue($response, $properties['thumbnailUrl']);
        $this->assertEquals($properties['thumbnailUrl'], $response->getThumbnailUrl());

        $property = $reflection->getProperty('thumbnailHeight');
        $property->setAccessible(true);
        $property->setValue($response, $properties['thumbnailHeight']);
        $this->assertEquals($properties['thumbnailHeight'], $response->getThumbnailHeight());

        $property = $reflection->getProperty('thumbnailWidth');
        $property->setAccessible(true);
        $property->setValue($response, $properties['thumbnailWidth']);
        $this->assertEquals($properties['thumbnailWidth'], $response->getThumbnailWidth());
    }

    public function responseProvider()
    {
        return array(
          array(
            [
              'title' => 'Hey there',
              'type' => 'video',
              'version' => '1.0',
              'authorName' => 'A Person',
              'authorUrl' => 'http://example.com',
              'providerName' => 'A Company',
              'providerUrl' => 'http://company.com',
              'cacheAge' => 3600,
              'thumbnailUrl' => 'http://example.com/thumbnail.gif',
              'thumbnailHeight' => 100,
              'thumbnailWidth' => 100,
            ],
          ),
        );
    }
}

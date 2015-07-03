<?php

namespace Bangpound\oEmbed\Test\Response;

use Bangpound\oEmbed\Response\PhotoResponse;

class PhotoResponseTest  extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider responseProvider
     */
    public function testString($url, $width, $height, $title, $string)
    {
        $response = new PhotoResponse();
        $reflection = new \ReflectionClass($response);

        $property = $reflection->getProperty('url');
        $property->setAccessible(true);
        $property->setValue($response, $url);

        $property = $reflection->getProperty('width');
        $property->setAccessible(true);
        $property->setValue($response, $width);

        $property = $reflection->getProperty('height');
        $property->setAccessible(true);
        $property->setValue($response, $height);

        $property = $reflection->getProperty('title');
        $property->setAccessible(true);
        $property->setValue($response, $title);

        $this->assertEquals($string, (string) $response);
    }

    public function responseProvider()
    {
        return array(
            ['http://example.com/image.gif', 400, 600, 'I don\'t even know.', '<img src="http://example.com/image.gif" width="400" height="600" alt="I don\'t even know." />'],
        );
    }
}

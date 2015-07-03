<?php

namespace Bangpound\oEmbed\Test\Response;

use Bangpound\oEmbed\Response\VideoResponse;

class VideoResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider responseProvider
     */
    public function testString($html, $string)
    {
        $response = new VideoResponse();

        $reflection = new \ReflectionClass($response);

        $property = $reflection->getProperty('html');
        $property->setAccessible(true);
        $property->setValue($response, $html);

        $this->assertEquals($string, (string) $response);
    }

    public function responseProvider()
    {
        return array(
          ['<iframe>', '<iframe>'],
        );
    }
}

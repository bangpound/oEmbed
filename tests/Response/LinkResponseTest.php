<?php

namespace Bangpound\oEmbed\Test\Response;

use Bangpound\oEmbed\Response\LinkResponse;

class LinkResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider responseProvider
     */
    public function testString($title, $string)
    {
        $response = new LinkResponse();
        $reflection = new \ReflectionClass($response);

        $property = $reflection->getProperty('title');
        $property->setAccessible(true);
        $property->setValue($response, $title);

        $this->assertEquals($string, (string) $response);
    }

    public function responseProvider()
    {
        return array(
            ['title', '<a href="%s">title</a>'],
            ['title & title', '<a href="%s">title &amp; title</a>'],
        );
    }
}

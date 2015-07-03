<?php

namespace Bangpound\oEmbed\Test\Response;

use Bangpound\oEmbed\Response\RichResponse;

class RichResponseTest  extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider responseProvider
     */
    public function testString($html, $string)
    {
        $response = new RichResponse();

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

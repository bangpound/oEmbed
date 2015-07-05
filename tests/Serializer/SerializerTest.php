<?php

namespace Bangpound\oEmbed\Test\Serializer;

use Bangpound\oEmbed\Response\LinkResponse;
use Bangpound\oEmbed\Response\Response;
use Bangpound\oEmbed\Serializer\Serializer;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSerializer
     *
     * @param Response $data
     * @param string   $format
     * @param string   $expected
     */
    public function testSerialize(Response $data, $format, $expected)
    {
        $serializer = Serializer::create();
        $result = $serializer->serialize($data, $format);
        $this->assertEquals($expected, $result);

        $class = get_class($data);
        $result = $serializer->deserialize($result, $class, $format);
        $this->assertEquals($data, $result);
    }

    /**
     * @dataProvider provideSerializerWithMap
     *
     * @param Response $data
     * @param string   $format
     * @param array    $map
     * @param string   $expected
     */
    public function testSerializeWithMap(Response $data, $format, array $map = array(), $expected)
    {
        $serializer = Serializer::create($map);
        $result = $serializer->serialize($data, $format);
        $this->assertEquals($expected, $result);

        $class = get_class($data);
        $result = $serializer->deserialize($result, $class, $format);
        $this->assertInstanceOf($class, $result);
    }

    public function provideSerializer()
    {
        return array(
          [new Response(), 'json', '{"type":null,"version":"1.0","title":null,"author_name":null,"author_url":null,"provider_name":null,"provider_url":null,"cache_age":null,"thumbnail_url":null,"thumbnail_width":null,"thumbnail_height":null}'],
          [new Response(), 'xml', '<?xml version="1.0"?>'.PHP_EOL.'<response><type/><version>1.0</version><title/><author_name/><author_url/><provider_name/><provider_url/><cache_age/><thumbnail_url/><thumbnail_width/><thumbnail_height/></response>'.PHP_EOL],
          [new LinkResponse(), 'json', '{"type":null,"version":"1.0","title":null,"author_name":null,"author_url":null,"provider_name":null,"provider_url":null,"cache_age":null,"thumbnail_url":null,"thumbnail_width":null,"thumbnail_height":null}'],
          [new LinkResponse(), 'xml', '<?xml version="1.0"?>'.PHP_EOL.'<response><type/><version>1.0</version><title/><author_name/><author_url/><provider_name/><provider_url/><cache_age/><thumbnail_url/><thumbnail_width/><thumbnail_height/></response>'.PHP_EOL],
        );
    }

    public function provideSerializerWithMap()
    {
        return array(
          [new Response(), 'json', [], '{"type":null,"version":"1.0","title":null,"author_name":null,"author_url":null,"provider_name":null,"provider_url":null,"cache_age":null,"thumbnail_url":null,"thumbnail_width":null,"thumbnail_height":null}'],
          [new Response(), 'xml', [], '<?xml version="1.0"?>'.PHP_EOL.'<response><type/><version>1.0</version><title/><author_name/><author_url/><provider_name/><provider_url/><cache_age/><thumbnail_url/><thumbnail_width/><thumbnail_height/></response>'.PHP_EOL],
          [new LinkResponse(), 'json', [], '{"type":null,"version":"1.0","title":null,"author_name":null,"author_url":null,"provider_name":null,"provider_url":null,"cache_age":null,"thumbnail_url":null,"thumbnail_width":null,"thumbnail_height":null}'],
          [new LinkResponse(), 'xml', [], '<?xml version="1.0"?>'.PHP_EOL.'<response><type/><version>1.0</version><title/><author_name/><author_url/><provider_name/><provider_url/><cache_age/><thumbnail_url/><thumbnail_width/><thumbnail_height/></response>'.PHP_EOL],
        );
    }
}

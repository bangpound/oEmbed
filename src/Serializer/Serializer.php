<?php

namespace Bangpound\oEmbed\Serializer;

use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    /**
     * @var SymfonySerializer
     */
    private $serializer;

    /**
     * @var array
     */
    private $map = array(
        'video' => 'Bangpound\\oEmbed\\Response\\VideoResponse',
        'photo' => 'Bangpound\\oEmbed\\Response\\PhotoResponse',
        'link' => 'Bangpound\\oEmbed\\Response\\LinkResponse',
        'rich' => 'Bangpound\\oEmbed\\Response\\RichResponse',
    );

    public function __construct(SymfonySerializer $serializer, array $map = array())
    {
        $this->serializer = $serializer;
        if (!empty($map)) {
            $this->map = $map;
        }
    }

    public function deserialize($data, $type, $format, array $context = array())
    {
        if (!$type) {
            $temp = $this->serializer->decode($data, $format, $context);
            $type = isset($this->map[$temp['type']]) ? $this->map[$temp['type']] : 'Bangpound\\oEmbed\\Response\\Response';
        }

        return $this->serializer->deserialize($data, $type, $format, $context);
    }

    public function serialize($data, $format, array $context = array())
    {
        return $this->serializer->serialize($data, $format, $context);
    }
}

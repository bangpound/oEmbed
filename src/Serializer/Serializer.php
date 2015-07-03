<?php

namespace Bangpound\oEmbed\Serializer;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    /**
     * @var \Symfony\Component\Serializer\Serializer
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

    public function __construct(SerializerInterface $serializer, array $map = array())
    {
        $this->serializer = $serializer;
        if (!empty($map)) {
            $this->map = $map;
        }
    }

    public function deserialize($data, $type, $format, array $context = array())
    {
        if ($type) {
            return $this->serializer->deserialize($data, $type, $format, $context);
        }

        if (!$this->serializer->supportsDecoding($format)) {
            throw new UnexpectedValueException(sprintf('Deserialization for the format %s is not supported', $format));
        }

        $data = $this->serializer->decode($data, $format, $context);

        if (!$type) {
            $type = isset($this->map[$data['type']]) ? $this->map[$data['type']] : 'Bangpound\oEmbed\Response\Response';
        }

        return $this->serializer->denormalize($data, $type, $format, $context);
    }

    public function serialize($data, $format, array $context = array())
    {
        return $this->serializer->serialize($data, $format, $context);
    }
}

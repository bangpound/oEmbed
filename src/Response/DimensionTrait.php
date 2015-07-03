<?php

namespace Bangpound\oEmbed\Response;

trait DimensionTrait
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @return string
     */
    public function getWidth()
    {
        return (int) $this->width;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return (int) $this->height;
    }
}

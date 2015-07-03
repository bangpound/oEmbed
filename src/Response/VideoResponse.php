<?php

namespace Bangpound\oEmbed\Response;

/**
 */
class VideoResponse extends Response
{
    private $html;
    private $height;
    private $width;

    public function getHtml()
    {
        return $this->html;
    }

    public function __toString()
    {
        return $this->getHtml();
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return (int) $this->height;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return (int) $this->width;
    }
}

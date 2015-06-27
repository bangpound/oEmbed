<?php

namespace Bangpound\oEmbed\Response;

class PhotoResponse extends Response
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $width;
    /**
     * @var string
     */
    private $height;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function __toString()
    {
        $title = isset($this->title) ? $this->title : null;

        return sprintf('<img src="%s" width="%s" height="%s" alt="%s" />', $this->url, $this->width, $this->height, $title);
    }
}

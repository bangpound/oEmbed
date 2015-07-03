<?php

namespace Bangpound\oEmbed\Response;

class PhotoResponse extends Response
{
    use DimensionTrait;

    /**
     * @var string
     */
    private $url;


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function __toString()
    {
        $title = isset($this->title) ? $this->title : null;

        return sprintf('<img src="%s" width="%s" height="%s" alt="%s" />', $this->url, $this->width, $this->height, $title);
    }
}

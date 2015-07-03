<?php

namespace Bangpound\oEmbed\Response;

/**
 * Class PhotoResponse.
 */
class PhotoResponse extends Response
{
    use DimensionTrait;

    /**
     * @var string
     */
    protected $url;

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
    public function __toString()
    {
        $title = isset($this->title) ? $this->title : null;

        return sprintf('<img src="%s" width="%s" height="%s" alt="%s" />', $this->url, $this->width, $this->height, $title);
    }
}

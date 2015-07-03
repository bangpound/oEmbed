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
        return sprintf('<img src="%s" width="%u" height="%u" alt="%s" />', filter_var($this->getUrl(), FILTER_SANITIZE_FULL_SPECIAL_CHARS), $this->getWidth(), $this->getHeight(), $this->getTitle());
    }
}

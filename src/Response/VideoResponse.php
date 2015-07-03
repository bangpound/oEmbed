<?php

namespace Bangpound\oEmbed\Response;

/**
 * Class VideoResponse.
 */
class VideoResponse extends Response
{
    use DimensionTrait;

    /**
     * @var string
     */
    protected $html;

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getHtml();
    }
}

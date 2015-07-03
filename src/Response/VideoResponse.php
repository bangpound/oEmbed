<?php

namespace Bangpound\oEmbed\Response;

/**
 */
class VideoResponse extends Response
{
    use DimensionTrait;

    protected $html;

    public function getHtml()
    {
        return $this->html;
    }

    public function __toString()
    {
        return $this->getHtml();
    }
}

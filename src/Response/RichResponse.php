<?php

namespace Bangpound\oEmbed\Response;

/**
 */
class RichResponse extends Response
{
    use DimensionTrait;

    private $html;

    public function getHtml()
    {
        return $this->html;
    }

    public function __toString()
    {
        return $this->getHtml();
    }
}

<?php

namespace Bangpound\oEmbed\Response;

/**
 */
class RichResponse extends Response
{
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

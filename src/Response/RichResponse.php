<?php

namespace Bangpound\oEmbed\Response;

/**
 * Class RichResponse.
 */
class RichResponse extends Response
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

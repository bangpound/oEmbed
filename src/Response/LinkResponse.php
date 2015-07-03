<?php

namespace Bangpound\oEmbed\Response;

/**
 * Class LinkResponse.
 */
class LinkResponse extends Response
{
    private $url;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('<a href="%s">%s</a>', $this->url, $this->title);
    }
}

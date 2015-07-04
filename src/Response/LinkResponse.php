<?php

namespace Bangpound\oEmbed\Response;

/**
 * Class LinkResponse.
 */
class LinkResponse extends Response
{
    public function __toString()
    {
        return sprintf('<a href="%%s">%s</a>', filter_var($this->getTitle(), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }
}

<?php

namespace Bangpound\oEmbed\Negotiation;

class FormatNegotiator extends \Negotiation\FormatNegotiator
{
    protected $formats = array(
      'json' => array('application/json', 'application/x-json', 'application/json+oembed'),
      'xml' => array('text/xml', 'application/xml', 'application/x-xml', 'text/xml+oembed'),
    );
}

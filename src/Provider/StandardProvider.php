<?php

namespace Bangpound\oEmbed\Provider;

/**
 * Class StandardProvider.
 */
class StandardProvider implements ProviderInterface
{
    private $name;
    private $urlScheme;
    private $endpoint;

    public function __construct($name, $endpoint, $urlScheme = array())
    {
        $this->name = $name;
        $this->urlScheme = $urlScheme;
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getUrlScheme()
    {
        return $this->urlScheme;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}

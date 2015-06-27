<?php

namespace Bangpound\oEmbed\Provider;

/**
 * Class StandardProvider.
 */
class StandardProvider implements ProviderInterface
{
    private $scheme;
    private $endpoint;

    /**
     * @param $endpoint
     * @param array $scheme
     */
    public function __construct($endpoint, $scheme = array())
    {
        $this->scheme = $scheme;
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, $params = array())
    {
        if (empty($this->scheme)) {
            return false;
        }
        $patterns = array();
        foreach ($this->scheme as $scheme) {
            $patterns[] = str_replace('\*', '.*', preg_quote($scheme, '#'));
        }

        $pattern = '#'.implode('|', $patterns).'#i';

        return preg_match($pattern, $url);
    }
}

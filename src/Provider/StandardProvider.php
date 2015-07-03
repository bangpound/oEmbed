<?php

namespace Bangpound\oEmbed\Provider;

use GuzzleHttp\Psr7;

/**
 * Class StandardProvider.
 */
class StandardProvider implements ProviderInterface
{
    private $scheme;
    private $endpoint;
    /**
     * @var array
     */
    private $requirements;
    /**
     * @var array
     */
    private $defaults;

    /**
     * @param $endpoint
     * @param array $scheme
     * @param array $requirements
     * @param array $defaults
     */
    public function __construct($endpoint, array $scheme = array(), array $requirements = array(), array $defaults = array())
    {
        $this->endpoint = $endpoint;
        $this->scheme = $scheme;
        $this->requirements = $requirements;
        $this->defaults = $defaults;
    }

    public function request($url, $params = array())
    {
        $uri = $this->makeUri($url, $params);

        return new Psr7\Request('get', $uri);
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
        $params = array_merge($this->defaults, $params);
        if (empty($this->scheme)) {
            return false;
        }

        $patterns = array_map(function ($scheme) {
            return str_replace('\*', '.*', preg_quote($scheme, '#'));
        }, $this->scheme);

        $pattern = '#'.implode('|', $patterns).'#i';

        return preg_match($pattern, $url) && !array_diff_key($this->requirements, $params);
    }

    private function makeUri($url, $params = array())
    {
        $uri = \GuzzleHttp\uri_template($this->endpoint, array_merge($this->defaults, $params));
        $uri = new Psr7\Uri($uri);

        // All arguments must be urlencoded (as per RFC 1738).
        $query = Psr7\build_query($params, PHP_QUERY_RFC1738);
        $uri = $uri->withQuery($query);

        return Psr7\Uri::withQueryValue($uri, 'url', $url);
    }
}

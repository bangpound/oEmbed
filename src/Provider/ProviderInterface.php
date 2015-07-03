<?php

namespace Bangpound\oEmbed\Provider;

/**
 * Interface ProviderInterface.
 */
interface ProviderInterface
{
    /**
     * @param $url
     * @param array $params
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function request($url, $params = array());

    /**
     * Returns whether this class supports the given url.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, $params = array());
}

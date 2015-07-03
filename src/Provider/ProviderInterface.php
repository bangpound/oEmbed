<?php

namespace Bangpound\oEmbed\Provider;

use Psr\Http\Message\RequestInterface;

/**
 * Interface ProviderInterface.
 */
interface ProviderInterface
{
    /**
     * @param $url
     * @param array $params
     *
     * @return RequestInterface
     */
    public function request($url, array $params = array());

    /**
     * Returns whether this class supports the given url.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, array $params = array());
}

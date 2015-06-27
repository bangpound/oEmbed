<?php

namespace Bangpound\oEmbed\Provider;

/**
 * Interface ProviderInterface.
 */
interface ProviderInterface
{
    /**
     * @return string
     */
    public function getEndpoint();

    /**
     * Returns whether this class supports the given resource.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, $params = array());
}

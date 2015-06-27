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
    public function getName();

    /**
     * @return array
     */
    public function getUrlScheme();

    /**
     * @return string
     */
    public function getEndpoint();
}

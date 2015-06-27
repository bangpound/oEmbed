<?php

namespace Bangpound\oEmbed\Provider;

interface ProviderResolverInterface
{
    /**
     * @param string $url
     * @param array  $params
     *
     * @return ProviderInterface|bool
     */
    public function resolve($url, $params = array());
}

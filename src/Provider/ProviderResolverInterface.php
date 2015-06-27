<?php

namespace Bangpound\oEmbed\Provider;

interface ProviderResolverInterface
{
    /**
     * @param $url
     * @param array $params
     *
     * @return bool
     */
    public function resolve($url, $params = array());
}

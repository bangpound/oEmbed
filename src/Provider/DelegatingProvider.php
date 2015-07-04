<?php

namespace Bangpound\oEmbed\Provider;

use Psr\Http\Message\RequestInterface;

class DelegatingProvider implements ProviderInterface
{
    private $resolver;

    public function __construct(ProviderResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param $url
     * @param array $params
     *
     * @return RequestInterface
     */
    public function request($url, array $params = array())
    {
        if (false === $provider = $this->resolver->resolve($url, $params)) {
            throw new \RuntimeException();
        }

        return $provider->request($url, $params);
    }

    /**
     * Returns whether this class supports the given url.
     *
     * @param string $url    A url
     * @param array  $params The resource type or null if unknown
     *
     * @return bool True if this class supports the given url, false otherwise
     */
    public function supports($url, array $params = array())
    {
        return false !== $this->resolver->resolve($url, $params);
    }
}

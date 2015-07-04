<?php

namespace Bangpound\oEmbed\Provider;

class ProviderResolver implements ProviderResolverInterface
{
    /**
     * @var ProviderInterface[] An array of ProviderInterface objects
     */
    private $providers = array();

    /**
     * Constructor.
     *
     * @param ProviderInterface[] $providers An array of providers
     */
    public function __construct(array $providers = array())
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($url, array $params = array())
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($url, $params)) {
                return $provider;
            }
        }

        return false;
    }

    /**
     * Adds a provider.
     *
     * @param ProviderInterface $provider A ProviderInterface instance
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * Returns the registered providers.
     *
     * @return ProviderInterface[] An array of ProviderInterface instances
     */
    public function getProviders()
    {
        return $this->providers;
    }
}

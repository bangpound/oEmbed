<?php

namespace Bangpound\oEmbed\Response;

/**
 * Class Response.
 */
class Response
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $version = '1.0';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $authorName;

    /**
     * @var string
     */
    protected $authorUrl;

    /**
     * @var string
     */
    protected $providerName;

    /**
     * @var string
     */
    protected $providerUrl;

    /**
     * @var int
     */
    protected $cacheAge;

    /**
     * @var string
     */
    protected $thumbnailUrl;

    /**
     * @var int
     */
    protected $thumbnailWidth;

    /**
     * @var int
     */
    protected $thumbnailHeight;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @return string
     */
    public function getProviderUrl()
    {
        return $this->providerUrl;
    }

    /**
     * @return int
     */
    public function getCacheAge()
    {
        return $this->cacheAge;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * @return int
     */
    public function getThumbnailWidth()
    {
        return (int) $this->thumbnailWidth;
    }

    /**
     * @return int
     */
    public function getThumbnailHeight()
    {
        return (int) $this->thumbnailHeight;
    }
}

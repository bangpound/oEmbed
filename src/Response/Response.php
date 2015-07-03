<?php

namespace Bangpound\oEmbed\Response;

class Response
{
    protected $type;
    protected $version = '1.0';
    protected $title;
    protected $authorName;
    protected $authorUrl;
    protected $providerName;
    protected $providerUrl;
    protected $cacheAge;
    protected $thumbnailUrl;
    protected $thumbnailWidth;
    protected $thumbnailHeight;

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return mixed
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * @return mixed
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @return mixed
     */
    public function getProviderUrl()
    {
        return $this->providerUrl;
    }

    /**
     * @return mixed
     */
    public function getCacheAge()
    {
        return $this->cacheAge;
    }

    /**
     * @return mixed
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

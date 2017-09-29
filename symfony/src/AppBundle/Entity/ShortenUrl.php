<?php

namespace AppBundle\Entity;

/**
 * ShortenUrl
 */
class ShortenUrl
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $original_url;

    /**
     * @var string
     */
    private $short_url;

    /**
     * @var int
     */
    private $use_count;

    /**
     * @var \DateTime
     */
    private $create_date;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set originalUrl
     *
     * @param string $originalUrl
     *
     * @return ShortenUrl
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->original_url = $originalUrl;

        return $this;
    }

    /**
     * Get originalUrl
     *
     * @return string
     */
    public function getOriginalUrl()
    {
        return $this->original_url;
    }

    /**
     * Set shortUrl
     *
     * @param string $shortUrl
     *
     * @return ShortenUrl
     */
    public function setShortUrl($shortUrl)
    {
        $this->short_url = $shortUrl;

        return $this;
    }

    /**
     * Get shortUrl
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->short_url;
    }

    /**
     * Set useCount
     *
     * @param integer $useCount
     *
     * @return ShortenUrl
     */
    public function setUseCount($useCount)
    {
        $this->use_count = $useCount;

        return $this;
    }

    /**
     * Get useCount
     *
     * @return int
     */
    public function getUseCount()
    {
        return $this->use_count;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return ShortenUrl
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }
}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShortenUrl
 *
 * @ORM\Table(name="shorten_url")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShortenUrlRepository")
 */
class ShortenUrl
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="original_url", type="string", length=255)
     */
    private $original_url;

    /**
     * @var string
     *
     * @ORM\Column(name="short_url", type="string", length=255)
     */
    private $short_url;

    /**
     * @var int
     *
     * @ORM\Column(name="use_count", type="integer", options={"default":0})
     */
    private $use_count = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date")
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
     * Increment useCount
     *
     * @return ShortenUrl
     */
    public function incrementUseCount()
    {
        $this->use_count++;

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


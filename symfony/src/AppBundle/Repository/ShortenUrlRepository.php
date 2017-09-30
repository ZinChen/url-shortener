<?php

namespace AppBundle\Repository;

/**
 * ShortenUrlRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShortenUrlRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Check if record with the same short url already exists
     * @param  string $shortUrl
     * @return boolean
     */
    public function isShortUrlExists($shortUrl)
    {
        $result = $this->findOneBy(
            array('short_url' => $shortUrl)
        );

        return count($result);
    }

}
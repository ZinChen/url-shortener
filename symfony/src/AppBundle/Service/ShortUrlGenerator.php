<?php

namespace AppBundle\Service;

class ShortUrlGenerator
{

    protected $length;
    protected $useDigits;

    public function __construct($shortUrlLength, $useDigits)
    {
        $this->length = max(array($shortUrlLength, 5));
        $this->useDigits = $useDigits;
    }

    /**
     * Get valid chars for short url
     * @return string
     */
    public function getChars()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($this->useDigits) {
          $chars .= '0123456789';
        }
        return $chars;
    }

    /**
     * Validate short url chars and length
     * @param  string  $shortUrl
     * @return boolean
     */
    public function validateChars($shortUrl)
    {
        $chars = $this->getChars();
        return strlen($shortUrl) === strspn($shortUrl, $chars);
    }

    /**
     * Validate short url length
     * @param  string  $shortUrl
     * @return boolean
     */
    public function validateLength($shortUrl)
    {
        $length = $this->length;
        return strlen($shortUrl) === $length;
    }

    /**
     * Validate real url
     * @param  string  $url
     * @return boolean
     */
    public function validateUrl($url)
    {
        $headers = @get_headers($url, 1);
        return $headers && isset($headers[0]) && strpos($headers[0],'200');
    }

    /**
     * Generate short url
     * @return $string
     */
    public function generate()
    {
        $length = $this->length;
        $chars = $this->getChars();

        $charsLength = strlen($chars);
        $randomUrl = '';
        for ($i = 0; $i < $length; $i++) {
            $randomUrl .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomUrl;
    }

}
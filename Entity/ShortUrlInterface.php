<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Entity;

/**
 * Interface ShortUrlInterface
 * @package Gomeeki\Bundle\UrlShortenerBundle\Entity
 */
interface ShortUrlInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return boolean
     */
    public function isCustomCode();

    /**
     * @param boolean $customCode
     *
     * @return $this
     */
    public function setCustomCode($customCode);

    /**
     * @return int
     */
    public function getHits();

    /**
     * @param int $hits
     *
     * @return $this
     */
    public function setHits($hits);

    /**
     * @param int $inc
     *
     * @return $this
     */
    public function addHits($inc = 1);
}

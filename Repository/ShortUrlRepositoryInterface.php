<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Repository;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;

/**
 * Interface ShortUrlRepositoryInterface
 * @package Gomeeki\Bundle\UrlShortenerBundle\Repository
 */
interface ShortUrlRepositoryInterface
{
    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrlInterface
     */
    public function findMatchingByShortCode(ShortUrlInterface $shortUrl);

    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrlInterface
     */
    public function findMatchingByUrl(ShortUrlInterface $shortUrl);

    /**
     * @param string $shortCode
     * @return ShortUrlInterface
     */
    public function findByShortCode($shortCode);

    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrlInterface
     */
    public function save(ShortUrlInterface $shortUrl);
}

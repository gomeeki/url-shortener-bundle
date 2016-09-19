<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Factory;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;

/**
 * Interface ShortUrlEntityFactoryInterface
 * @package Gomeeki\Bundle\UrlShortenerBundle\Factory
 */
interface ShortUrlEntityFactoryInterface
{
    /**
     * @param array $data
     * @return ShortUrlInterface
     */
    public function createFromArray(array $data);
}

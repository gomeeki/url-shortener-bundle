<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Event;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ShortUrlRedirectedEvent
 * @package Gomeeki\Bundle\UrlShortenerBundle\Event
 */
class ShortUrlRedirectedEvent extends Event
{
    /**
     * @var ShortUrlInterface
     */
    private $shortUrl;

    /**
     * ShortUrlRedirectedEvent constructor.
     * @param ShortUrlInterface $shortUrl
     */
    public function __construct(ShortUrlInterface $shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return ShortUrlInterface
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }
}

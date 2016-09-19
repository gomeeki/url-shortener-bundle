<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Event;

/**
 * Class ShortUrlEvents
 * @package Gomeeki\Bundle\UrlShortenerBundle\Event
 */
final class ShortUrlEvents
{
    /**
     * Events
     */
    const SHORT_URL_CREATED = 'gomeeki_url_shortener.short_url_created';
    const SHORT_URL_REDIRECTED = 'gomeeki_url_shortener.short_url_redirected';
}

<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Factory;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrl;
use Gomeeki\Bundle\UrlShortenerBundle\Exception\UrlIsNotValidException;

/**
 * Class ShortUrlEntityFactory
 * @package Gomeeki\Bundle\UrlShortenerBundle\Factory
 */
class ShortUrlEntityFactory implements ShortUrlEntityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFromArray(array $data)
    {
        // validate url
        if (!isset($data['url']) || (filter_var($data['url'], FILTER_VALIDATE_URL) === false)) {
            throw new UrlIsNotValidException('Sorry, your url is not valid');
        }

        $url = rtrim($data['url'], '/');

        $shortUrl = new ShortUrl();
        $shortUrl->setUrl($url);

        $code = null;
        if (isset($data['code']) && $data['code'] !== '') {
            $code = $data['code'];
            $shortUrl->setCode($code);
            $shortUrl->setCustomCode(true);
        }

        return $shortUrl;
    }
}

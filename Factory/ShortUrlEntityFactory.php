<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Factory;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrl;
use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Exception\UrlIsNotValidException;

/**
 * Class ShortUrlEntityFactory
 * @package Gomeeki\Bundle\UrlShortenerBundle\Factory
 */
class ShortUrlEntityFactory implements ShortUrlEntityFactoryInterface
{
    /**
     * @var string
     */
    protected $shortUrlEntityClass;

    /**
     * ShortUrlEntityFactory constructor.
     * @param string $shortUrlEntityClass
     */
    public function __construct($shortUrlEntityClass)
    {
        $this->shortUrlEntityClass = $shortUrlEntityClass;
    }

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

        /** @var ShortUrlInterface $shortUrl */
        $shortUrl = new $this->shortUrlEntityClass();
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

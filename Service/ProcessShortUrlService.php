<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Service;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrl;
use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlCreatedEvent;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlEvents;
use Gomeeki\Bundle\UrlShortenerBundle\Exception\CustomCodeAlreadyTakenException;
use Gomeeki\Bundle\UrlShortenerBundle\Exception\DecodeUnallowedResultException;
use Gomeeki\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Hashids\Hashids;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ProcessShortUrlService
 * @package Gomeeki\Bundle\UrlShortenerBundle\Service
 */
class ProcessShortUrlService
{
    /** @var Hashids */
    private $hashids;

    /** @var ShortUrlRepositoryInterface $repository */
    private $urlRepository;

    /** @var EventDispatcherInterface $dispatcher */
    private $dispatcher;

    /**
     * ProcessShortUrlService constructor.
     * @param Hashids $hashids
     * @param ShortUrlRepositoryInterface $urlRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        Hashids $hashids,
        ShortUrlRepositoryInterface $urlRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->hashids = $hashids;
        $this->urlRepository = $urlRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrlInterface
     */
    public function process(ShortUrlInterface $shortUrl)
    {
        // check if we already have the code
        $existingShortUrl = $this->urlRepository->findMatchingByUrl($shortUrl);

        // If we have one then return it
        if (null !== $existingShortUrl) {
            return $existingShortUrl;
        }

        // 1. persists
        $this->urlRepository->save($shortUrl);

        // 2. generate code
        $shortUrlId = $shortUrl->getId();
        $code = $this->hashids->encode($shortUrlId);

        // 3. update code in db
        $shortUrl->setCode($code);
        $shortUrl = $this->urlRepository->save($shortUrl);

        // 4. dispatch ShortUrlCreated event
        $event = new ShortUrlCreatedEvent($shortUrl);
        $this->dispatcher->dispatch(ShortUrlEvents::SHORT_URL_CREATED, $event);

        return $shortUrl;
    }

    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrl|ShortUrlInterface
     * @throws CustomCodeAlreadyTakenException
     * @throws DecodeUnallowedResultException
     */
    public function processCustomCode(ShortUrlInterface $shortUrl)
    {
        // 1. check if custom code can be used
        $decodedCode = $this->hashids->decode($shortUrl->getCode());

        if (0 !== count($decodedCode)) {
            throw new DecodeUnallowedResultException('Sorry, your code is not available');
        }

        // 2. check custom code isn't already used (in the repository)
        if (null !== $this->urlRepository->findMatchingByShortCode($shortUrl)) {
            throw new CustomCodeAlreadyTakenException('Sorry, your code is not available');
        }

        // 3. persist entity
        $this->urlRepository->save($shortUrl);

        // 4. dispatch ShortUrlCreated event
        $event = new ShortUrlCreatedEvent($shortUrl);
        $this->dispatcher->dispatch(ShortUrlEvents::SHORT_URL_CREATED, $event);

        return $shortUrl;
    }
}

<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Service;

use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlRedirectedEvent;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class GetRedirectUrlService
 * @package Gomeeki\Bundle\UrlShortenerBundle\Service
 */
class GetRedirectUrlService
{
    /** @var ShortUrlRepositoryInterface */
    private $urlRepository;
    private $dispatcher;

    /**
     * GetRedirectUrlService constructor.
     * @param ShortUrlRepositoryInterface $urlRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(ShortUrlRepositoryInterface $urlRepository, EventDispatcherInterface $dispatcher)
    {
        $this->urlRepository = $urlRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $code
     * @return null|RedirectResponse
     */
    public function getRedirectResponse($code)
    {
        // 1. find a short url entity
        $shortUrl = $this->urlRepository->findByShortCode($code);

        if (!$shortUrl) {
            return null;
        }

        // 2. increase number of hits by one
        $hits = $shortUrl->getHits();
        $shortUrl->setHits(++$hits);
        $shortUrl = $this->urlRepository->save($shortUrl);

        // 3. dispatch ShortUrlRedirected event
        $event = new ShortUrlRedirectedEvent($shortUrl);
        $this->dispatcher->dispatch(ShortUrlEvents::SHORT_URL_REDIRECTED, $event);

        // 4. create RedirectResponse and return it
        $targetUrl = $shortUrl->getUrl();

        return new RedirectResponse($targetUrl);
    }
}

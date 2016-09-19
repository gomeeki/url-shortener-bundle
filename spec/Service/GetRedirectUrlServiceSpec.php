<?php

namespace spec\Gomeeki\Bundle\UrlShortenerBundle\Service;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrl;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlEvents;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlRedirectedEvent;
use Gomeeki\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Service\GetRedirectUrlService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class GetRedirectUrlServiceSpec
 * @package spec\Gomeeki\Bundle\UrlShortenerBundle\Service
 * @mixin GetRedirectUrlService
 */
class GetRedirectUrlServiceSpec extends ObjectBehavior
{
    function let(ShortUrlRepositoryInterface $urlRepository, EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($urlRepository, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GetRedirectUrlService::class);
    }

    function it_get_redirect_response_by_nonexistent_shortcode(ShortUrlRepositoryInterface $urlRepository)
    {
        $shortCode = 'qwe';

        $urlRepository->findByShortCode($shortCode)
                      ->shouldBeCalledTimes(1)
                      ->willReturn(null);

        $this->getRedirectResponse($shortCode)->shouldReturn(null);
    }

    function it_get_redirect_response(
      ShortUrlRepositoryInterface $urlRepository,
      ShortUrl $shortUrl,
      EventDispatcherInterface $dispatcher
    ) {
        $shortCode = 'qwe';
        $targetUrl = 'http://google.com';

        $shortUrl->getHits()->willReturn(2);
        $shortUrl->getUrl()->willReturn($targetUrl);
        $shortUrl->setHits(3)->shouldBeCalled();

        $urlRepository->findByShortCode($shortCode)
                      ->shouldBeCalledTimes(1)
                      ->willReturn($shortUrl);

        $urlRepository->save(Argument::type(ShortUrl::class))
                      ->shouldBeCalledTimes(1)
                      ->willReturn($shortUrl);

        $dispatcher->dispatch(ShortUrlEvents::SHORT_URL_REDIRECTED, Argument::type(ShortUrlRedirectedEvent::class))
                   ->shouldBeCalledTimes(1);

        $redirectResponse = new RedirectResponse($targetUrl);
        $this->getRedirectResponse($shortCode)->shouldBeLike($redirectResponse);
    }
}

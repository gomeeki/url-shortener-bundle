<?php

namespace spec\Gomeeki\Bundle\UrlShortenerBundle\Service;

use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrl;
use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlEvents;
use Gomeeki\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Service\ProcessShortUrlService;
use Hashids\Hashids;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Event\ShortUrlCreatedEvent;
use Gomeeki\Bundle\UrlShortenerBundle\Exception\DecodeUnallowedResultException;
use Gomeeki\Bundle\UrlShortenerBundle\Exception\CustomCodeAlreadyTakenException;

/**
 * Class ProcessShortUrlServiceSpec
 * @package spec\Gomeeki\Bundle\UrlShortenerBundle\Service
 * @mixin ProcessShortUrlService
 */
class ProcessShortUrlServiceSpec extends ObjectBehavior
{
    function let(Hashids $hashids, ShortUrlRepositoryInterface $urlRepository, EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($hashids, $urlRepository, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProcessShortUrlService::class);
    }

    function it_will_return_existing_short_url_when_processing_non_custom_code(
        ShortUrlRepositoryInterface $urlRepository,
        ShortUrlInterface $shortUrl,
        ShortUrlInterface $existingShortUrl
    ) {
        $url = 'http://abc.com';

        $shortUrl->getUrl()->willReturn($url);

        $urlRepository->findMatchingByUrl($shortUrl)->willReturn($existingShortUrl);

        $this->process($shortUrl)->shouldReturn($existingShortUrl);
    }

    function it_will_process_and_save_new_short_url_without_custom_code(
        Hashids $hashids,
        ShortUrlRepositoryInterface $urlRepository,
        EventDispatcherInterface $dispatcher,
        ShortUrlInterface $shortUrl
    ) {
        $id = 1234;
        $url = 'http://abc.com';
        $code = 'abc123';

        $shortUrl->getId()->willReturn($id);
        $shortUrl->getUrl()->willReturn($url);
        $shortUrl->setCode($code)->shouldBeCalled();

        $urlRepository->findMatchingByUrl($shortUrl)->willReturn(null);

        $hashids->encode($id)->willReturn($code);

        $urlRepository->save($shortUrl)
            ->shouldBeCalledTimes(2)
            ->willReturn($shortUrl);

        $event = new ShortUrlCreatedEvent($shortUrl->getWrappedObject());
        $dispatcher->dispatch(ShortUrlEvents::SHORT_URL_CREATED, $event)->shouldBeCalledTimes(1);

        $this->process($shortUrl)->shouldReturn($shortUrl);
    }

    function it_will_throw_exception_when_processing_decodable_custom_code(
        Hashids $hashids,
        ShortUrlInterface $shortUrl
    ) {
        $customCode = 'this-is-custom';

        $shortUrl->getCode()->willReturn($customCode);

        $hashids->decode($customCode)->willReturn([1]);

        $this->shouldThrow(DecodeUnallowedResultException::class)->duringProcessCustomCode($shortUrl);
    }

    function it_will_throw_exception_when_processing_already_taken_custom_code(
        Hashids $hashids,
        ShortUrlRepositoryInterface $urlRepository,
        ShortUrlInterface $shortUrl,
        ShortUrlInterface $exisingShortUrl
    ) {
        $customCode = 'this-is-custom';

        $shortUrl->getCode()->willReturn($customCode);

        $hashids->decode($customCode)->willReturn([]);

        $urlRepository->findMatchingByShortCode($shortUrl)->willReturn($exisingShortUrl);

        $this->shouldThrow(CustomCodeAlreadyTakenException::class)->duringProcessCustomCode($shortUrl);
    }

    function it_will_save_new_custom_code(
        Hashids $hashids,
        ShortUrlRepositoryInterface $urlRepository,
        ShortUrlInterface $shortUrl,
        EventDispatcherInterface $dispatcher
    ) {
        $customCode = 'qwe';

        $shortUrl->getCode()->willReturn($customCode);

        $hashids->decode($customCode)->willReturn([]);

        $urlRepository->findMatchingByShortCode($shortUrl)->willReturn(null);

        $urlRepository->save($shortUrl)->shouldBeCalledTimes(1);

        $event = new ShortUrlCreatedEvent($shortUrl->getWrappedObject());
        $dispatcher->dispatch(ShortUrlEvents::SHORT_URL_CREATED, $event)->shouldBeCalledTimes(1);

        $this->processCustomCode($shortUrl)->shouldBe($shortUrl);
    }
}

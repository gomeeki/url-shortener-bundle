<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Controller;

use Gomeeki\Bundle\UrlShortenerBundle\Factory\ShortUrlEntityFactoryInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Service\ProcessShortUrlService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Gomeeki\Bundle\UrlShortenerBundle\Exception;

/**
 * Class ShortUrlController
 * @package Gomeeki\Bundle\UrlShortenerBundle\Controller
 */
class ShortUrlController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BadRequestHttpException
     */
    public function createShortUrlAction(Request $request)
    {
        /** @var ShortUrlEntityFactoryInterface $shortUrlFactory */
        /** @var ProcessShortUrlService $processShortUrlService */
        $shortUrlFactory = $this->get('gomeeki_url_shortener.factory.short_url');
        $processShortUrlService = $this->get('gomeeki_url_shortener.process');

        // 1. Get request data
        $data = $request->request->all();

        // 2. Create entity using request data and Entity Factory
        $shortUrl = $shortUrlFactory->createFromArray($data);

        // 3. Process the ShortUrl
        try {
            if ($shortUrl->isCustomCode()) {
                $shortUrl = $processShortUrlService->processCustomCode($shortUrl);
            } else {
                $shortUrl = $processShortUrlService->process($shortUrl);
            }
        } catch (Exception\DecodeUnallowedResultException $e){
            throw new BadRequestHttpException($e->getMessage(), $e);
        } catch (Exception\CustomCodeAlreadyTakenException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        // 4. Generate the Short Code Url
        if (null !== $this->getParameter('gomeeki_url_shortener.host_url')) {
            $path = $this->generateUrl('gomeeki_url_shortener_redirect', ['code' => $shortUrl->getCode()]);
            $shortCodeUrl = $this->getParameter('gomeeki_url_shortener.host_url') . $path;
        } else {
            $shortCodeUrl = $this->generateUrl(
                'gomeeki_url_shortener_redirect',
                ['code' => $shortUrl->getCode()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        // 5. Return the url in a JsonResponse
        return new JsonResponse(['url' => $shortUrl->getUrl(), 'short_url' => $shortCodeUrl]);
    }
}

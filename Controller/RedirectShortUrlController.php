<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RedirectShortUrlController
 * @package Gomeeki\Bundle\UrlShortenerBundle\Controller
 */
class RedirectShortUrlController extends Controller
{
    /**
     * @param string $code
     * @return RedirectResponse
     */
    public function redirectShortUrlAction($code)
    {
        $redirectUrlService = $this->get('gomeeki_url_shortener.get_redirect_response');

        // 1. Pass code and Request into to Get Redirect service
        $response = $redirectUrlService->getRedirectResponse($code);

        // 2. If result is null then throw 404
        if (null === $response) {
            throw new NotFoundHttpException("Page not found");
        }

        // 3. If result is a RedirectResponse then return it from the controller action
        return $response;
    }
}

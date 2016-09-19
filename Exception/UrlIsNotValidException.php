<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Exception;

/**
 * Class UrlIsNotValidException
 * @package Gomeeki\Bundle\UrlShortenerBundle\Exception
 */
class UrlIsNotValidException extends \Exception
{
    /**
     * UrlIsNotValidException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

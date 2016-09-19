<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Exception;

/**
 * Class CustomCodeAlreadyTakenException
 * @package Gomeeki\Bundle\UrlShortenerBundle\Exception
 */
class CustomCodeAlreadyTakenException extends \Exception
{
    /**
     * CustomCodeAlreadyTakenException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Exception;

/**
 * Class DecodeUnallowedResultException
 * @package Gomeeki\Bundle\UrlShortenerBundle\Exception
 */
class DecodeUnallowedResultException extends \Exception
{
    /**
     * DecodeUnallowedResultException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

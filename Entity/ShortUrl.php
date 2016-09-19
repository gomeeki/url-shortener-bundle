<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Gomeeki\Bundle\UrlShortenerBundle\Repository\DoctrineShortUrlRepository")
 * @ORM\Table(name="short_url")
 */
class ShortUrl extends AbstractShortUrl
{
    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * ShortUrl constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->customCode = false;
        $this->hits = 0;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }
}

<?php
namespace Gomeeki\Bundle\UrlShortenerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class AbstractShortUrl implements ShortUrlInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", unique=true, nullable=true)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="url", type="string")
     */
    protected $url;

    /**
     * @var bool
     * @ORM\Column(name="custom_code", type="boolean")
     */
    protected $customCode;

    /**
     * @var int
     * @ORM\Column(name="hits", type="integer")
     */
    protected $hits;

    /**
     * AbstractShortUrl constructor.
     */
    public function __construct()
    {
        $this->customCode = false;
        $this->hits = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCustomCode()
    {
        return $this->customCode;
    }

    /**
     * @param boolean $customCode
     *
     * @return $this
     */
    public function setCustomCode($customCode)
    {
        $this->customCode = $customCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * @param int $hits
     *
     * @return $this
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * @param int $inc
     *
     * @return $this
     */
    public function addHits($inc = 1)
    {
        $this->hits += $inc;

        return $this;
    }
}

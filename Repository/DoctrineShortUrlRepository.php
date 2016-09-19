<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Gomeeki\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;

/**
 * Class DoctrineShortUrlRepository
 * @package Gomeeki\Bundle\UrlShortenerBundle\Repository
 */
class DoctrineShortUrlRepository extends EntityRepository implements ShortUrlRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findMatchingByShortCode(ShortUrlInterface $shortUrl)
    {
        $qb = $this->createQueryBuilder('su');
        $qb->where('su.code = :shortcode')
            ->setParameter('shortcode', $shortUrl->getCode());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findMatchingByUrl(ShortUrlInterface $shortUrl)
    {
        $qb = $this->createQueryBuilder('su');
        $qb->where('su.url = :url')
            ->andWhere('su.customCode = false')
            ->setParameter('url', $shortUrl->getUrl());

        $results = $qb->getQuery()->getResult();

        return (0 === count($results)) ? null : $results[0];
    }

    /**
     * {@inheritdoc}
     */
    public function findByShortCode($shortCode)
    {
        $qb = $this->createQueryBuilder('su');
        $qb->where('su.code = :shortcode')
            ->setParameter('shortcode', $shortCode);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function save(ShortUrlInterface $shortUrl)
    {
        $this->_em->persist($shortUrl);
        $this->_em->flush($shortUrl);

        return $shortUrl;
    }
}

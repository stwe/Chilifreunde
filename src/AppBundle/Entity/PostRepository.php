<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository
 *
 * @package AppBundle\Entity
 */
class PostRepository extends EntityRepository
{
    /**
     * @param Season $season
     *
     * @return \Doctrine\ORM\Query
     */
    public function findPostsBySeason(Season $season)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.season', 's')
            ->where('s = :season');

        $qb->setParameter('season', $season);

        return $qb->getQuery();
    }

    /**
     * @param integer|null $limit
     *
     * @return array
     */
    public function getLatestPosts($limit = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->addOrderBy('p.publishedAt', 'DESC');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()
            ->getResult();
    }
}

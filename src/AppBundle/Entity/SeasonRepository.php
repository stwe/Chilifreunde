<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class SeasonRepository
 *
 * @package AppBundle\Entity
 */
class SeasonRepository extends EntityRepository
{
    /**
     * @param integer|null $limit
     *
     * @return array
     */
    public function getLatestSeasons($limit = null)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->addOrderBy('s.createdAt', 'DESC');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()
            ->getResult();
    }
}

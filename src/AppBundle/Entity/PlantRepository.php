<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class PlantRepository
 *
 * @package AppBundle\Entity
 */
class PlantRepository extends EntityRepository
{
    /**
     * Get Plant sum.
     *
     * @param integer $seasonId
     *
     * @return mixed
     */
    public function getPlantSum($seasonId)
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.quantity)')
            ->join('p.season', 's')
            ->where('s = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

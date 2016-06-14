<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LocationRepository
 *
 * @package AppBundle\Entity
 */
class LocationRepository extends EntityRepository
{
    /**
     * Find all Locations by User.
     *
     * @param UserInterface $user
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbFindAllLocationsByUser(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('l');
        $qb->join('l.user', 'u');
        $qb->where('u = :user');
        $qb->setParameter('user', $user);

        return $qb;
    }
}

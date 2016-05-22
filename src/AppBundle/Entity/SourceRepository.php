<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SourceRepository
 *
 * @package AppBundle\Entity
 */
class SourceRepository extends EntityRepository
{
    /**
     * Find all Sources by User.
     *
     * @param UserInterface $user
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbFindAllSourcesByUser(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->join('s.user', 'u');
        $qb->where('s.public = true');
        $qb->orWhere('s.public = false AND u = :user');
        $qb->setParameter('user', $user);

        return $qb;
    }
}

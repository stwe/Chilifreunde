<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use DateTime;

/**
 * Class UserRepository
 *
 * @package AppBundle\Entity
 */
class UserRepository extends EntityRepository
{
    /**
     * Get active users.
     *
     * @return array
     */
    public function getActive()
    {
        $delay = new DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));

        $qb = $this->createQueryBuilder('u')
            ->where('u.lastActivityAt > :delay')
            ->setParameter('delay', $delay)
        ;

        return $qb->getQuery()->getResult();
    }
}

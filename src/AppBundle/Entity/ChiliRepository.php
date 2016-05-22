<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ChiliRepository
 *
 * @package AppBundle\Entity
 */
class ChiliRepository extends EntityRepository
{
    /**
     * Find all Chilis by Species and public.
     *
     * @param boolean            $public
     * @param Species|null       $species
     * @param UserInterface|null $user
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbFindAllChilisBySpeciesAndPublic($public, Species $species = null, UserInterface $user = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c');

        if (false === $public) {
            $qb->where('c.public = false');
            $qb->join('c.user', 'u');
            $qb->andWhere('u = :user');
            $qb->setParameter('user', $user);
        } else {
            $qb->where('c.public = true');
        }

        if (null !== $species) {
            $qb->join('c.species', 's');
            $qb->andWhere('s = :species');
            $qb->setParameter('species', $species);
        }

        return $qb;
    }

    /**
     * Find all Chilis by Species and public.
     *
     * @param boolean            $public
     * @param Species|null       $species
     * @param UserInterface|null $user
     *
     * @return array
     */
    public function findAllChilisBySpeciesAndPublic($public, Species $species = null, UserInterface $user = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c');

        if (false === $public) {
            $qb->where('c.public = false');
            $qb->join('c.user', 'u');
            $qb->andWhere('u = :user');
            $qb->setParameter('user', $user);
        } else {
            $qb->where('c.public = true');
        }

        if (null !== $species) {
            $qb->join('c.species', 's');
            $qb->andWhere('s = :species');
            $qb->setParameter('species', $species);
        }

        return $qb->getQuery()->getResult();
    }
}

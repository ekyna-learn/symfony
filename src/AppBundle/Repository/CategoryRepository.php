<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Finds one category by slug.
     *
     * @param string $slug
     *
     * @return \AppBundle\Entity\Category|null
     */
    public function findOneBySlug($slug)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->where($qb->expr()->eq('c.slug', ':slug'))
            ->getQuery()
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
    }
}

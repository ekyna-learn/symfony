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
            ->andWhere($qb->expr()->eq('c.slug', ':slug'))
            ->andWhere($qb->expr()->eq('c.enabled', ':enabled'))
            ->getQuery()
            ->setParameters([
                'slug'    => $slug,
                'enabled' => true,
            ])
            ->getOneOrNullResult();
    }
}

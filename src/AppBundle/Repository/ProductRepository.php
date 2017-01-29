<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 */
class ProductRepository extends EntityRepository
{
    /**
     * Finds one product by slug.
     *
     * @param string $slug
     *
     * @return \AppBundle\Entity\Product|null
     */
    public function findOneBySlug($slug)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where($qb->expr()->eq('p.slug', ':slug'))
            ->getQuery()
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
    }

    /**
     * Finds products by category.
     *
     * @param Category $category
     *
     * @return \AppBundle\Entity\Product[]
     */
    public function findByCategory(Category $category)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where($qb->expr()->eq('p.category', ':category'))
            ->getQuery()
            ->setParameter('category', $category)
            ->getResult();
    }
}
